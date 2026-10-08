<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Branch;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    private function ensureBranchAccess(Warehouse $warehouse): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->canAccessBranch((int) $warehouse->branch_id), 403);
    }

    public function index()
    {
        $this->authorize('viewAny', Warehouse::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $warehouses = Warehouse::query()
            ->with('branch')
            ->whereIn('branch_id', $user->accessibleBranchIds())
            ->latest()
            ->paginate(15);

        return view('warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        $this->authorize('create', Warehouse::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branches = Branch::query()->where('status', 'active')->whereIn('id', $user->accessibleBranchIds())->orderBy('name')->get();

        return view('warehouses.create', compact('branches'));
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $this->authorize('create', Warehouse::class);
        $validated = $request->validated();
        $isFirstWarehouse = ! Warehouse::query()->where('branch_id', $validated['branch_id'])->exists();

        if ($isFirstWarehouse) {
            $validated['is_default'] = true;
        }

        if (($validated['is_default'] ?? false) === true) {
            Warehouse::query()
                ->where('branch_id', $validated['branch_id'])
                ->update(['is_default' => false]);
        }

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse): View
    {
        $this->authorize('view', $warehouse);
        $this->ensureBranchAccess($warehouse);
    
        $warehouse->load('branch');

        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse): View
    {
        $this->authorize('update', $warehouse);
        $this->ensureBranchAccess($warehouse);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branches = Branch::query()->where('status', 'active')->whereIn('id', $user->accessibleBranchIds())->orderBy('name')->get();

        return view('warehouses.edit', compact('warehouse', 'branches'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $this->authorize('update', $warehouse);
        $this->ensureBranchAccess($warehouse);

        $validated = $request->validated();

        if (($validated['is_default'] ?? false) === true) {
            Warehouse::query()
                ->where('branch_id', $validated['branch_id'])
                ->where('id', '!=', $warehouse->id)
                ->update(['is_default' => false]);
        }

        /*
         * Prevent a branch from ending up with no default
         * warehouse when this warehouse is currently the default.
         */
        if (
            $warehouse->is_default
            && ! ($validated['is_default'] ?? false)
            && $warehouse->branch_id == $validated['branch_id']
        ) {
            $validated['is_default'] = true;
        }

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    // public function destroy(Warehouse $warehouse): RedirectResponse
    // {
    //     $this->authorize('delete', $warehouse);
    //     $warehouse->delete();

    //     return redirect()->route('warehouses.index')->with('success', 'Warehouse moved to trash successfully.');
    // }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $this->authorize('delete', $warehouse);
        $this->ensureBranchAccess($warehouse);

        $wasDefault = $warehouse->is_default;
        $branchId = $warehouse->branch_id;
        $warehouse->delete();

        if ($wasDefault) {
            $newDefault = Warehouse::query()->where('branch_id', $branchId)->where('status', 'Active')->orderBy('id')->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Warehouse::class);
        $warehouses = Warehouse::onlyTrashed()->with('branch')->paginate(15);

        return view('warehouses.trash', compact('warehouses'));
    }

    public function restore(int $warehouse): RedirectResponse
    {
        $warehouse = Warehouse::onlyTrashed()->findOrFail($warehouse);
        $this->authorize('restore', $warehouse);
        $this->ensureBranchAccess($warehouse);

        $warehouse->restore();

        /*
         * If the branch has no other default warehouse,
         * make the restored warehouse the default.
         */
        $hasDefault = Warehouse::query()->where('branch_id', $warehouse->branch_id)->where('is_default', true)->exists();

        if (! $hasDefault) {
            $warehouse->update(['is_default' => true]);
        }

        return redirect()->route('warehouses.trash')->with('success', 'Warehouse restored successfully.');
    }

    public function forceDelete(int $warehouse): RedirectResponse
    {
        $warehouse = Warehouse::onlyTrashed()->findOrFail($warehouse);
        $this->authorize('forceDelete', $warehouse);
        $this->ensureBranchAccess($warehouse);
        
        $warehouse->forceDelete();

        return redirect()->route('warehouses.trash')->with('success', 'Warehouse permanently deleted.');
    }
}
