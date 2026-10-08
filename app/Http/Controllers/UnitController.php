<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Unit::class);
        $units = Unit::query()->latest()->paginate(15);

        return view('units.index', compact('units'));
    }

    public function create(): View
    {
        $this->authorize('create', Unit::class);

        return view('units.create');
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $this->authorize('create', Unit::class);
        $validated = $request->validated();

        /*
         * A non-decimal unit should always use
         * whole-number precision.
         */
        if (! $validated['allows_decimal']) {
            $validated['precision'] = 1;
        }

        Unit::create($validated);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit): View
    {
        $this->authorize('view', $unit);

        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit): View
    {
        $this->authorize('update', $unit);

        return view('units.edit', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $this->authorize('update', $unit);
        $validated = $request->validated();

        if (! $validated['allows_decimal']) {
            $validated['precision'] = 1;
        }

        $unit->update($validated);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $this->authorize('delete', $unit);
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Unit::class);
        $units = Unit::onlyTrashed()->paginate(15);

        return view('units.trash', compact('units'));
    }

    public function restore(int $unit): RedirectResponse
    {
        $unit = Unit::onlyTrashed()->findOrFail($unit);
        $this->authorize('restore', $unit);
        $unit->restore();

        return redirect()->route('units.trash')->with('success', 'Unit restored successfully.');
    }

    public function forceDelete(int $unit): RedirectResponse
    {
        $unit = Unit::onlyTrashed()->findOrFail($unit);
        $this->authorize('forceDelete', $unit);
        $unit->forceDelete();

        return redirect()->route('units.trash')->with('success', 'Unit permanently deleted.');
    }
}
