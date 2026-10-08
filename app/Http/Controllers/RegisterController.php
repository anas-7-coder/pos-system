<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterRequest;
use App\Http\Requests\UpdateRegisterRequest;
use App\Models\Branch;
use App\Models\Register;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    private function ensureBranchAccess(Register $register): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->canAccessBranch((int) $register->branch_id), 403);
    }

    public function index()
    {
        $this->authorize('viewAny', Register::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $registers = Register::query()
            ->with('branch')
            ->whereIn('branch_id', $user->accessibleBranchIds())
            ->latest()
            ->paginate(15);

        return view('registers.index', compact('registers'));
    }

    public function create(): View
    {
        $this->authorize('create', Register::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $branches = Branch::query()->where('status', 'Active')->whereIn('id', $user->accessibleBranchIds())->orderBy('name')->get();

        return view('registers.create', compact('branches'));
    }
    
    public function store(StoreRegisterRequest $request): RedirectResponse
    {
        $this->authorize('create', Register::class);
        Register::create($request->validated());

        return redirect()->route('registers.index')->with('success', 'Register created successfully.');
    }

    public function show(Register $register): View
    {
        $this->authorize('view', $register);
        $this->ensureBranchAccess($register);
        $register->load('branch');

        return view('registers.show', compact('register'));
    }

    public function edit(Register $register): View
    {
        $this->authorize('update', $register);
        $this->ensureBranchAccess($register);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branches = Branch::query()->where('status', 'Active')->whereIn('id', $user->accessibleBranchIds())->orderBy('name')->get();

        return view('registers.edit', compact('register', 'branches'));
    }

    public function update(UpdateRegisterRequest $request, Register $register): RedirectResponse
    {
        $this->authorize('update', $register);
        $this->ensureBranchAccess($register);
        $register->update($request->validated());

        return redirect()->route('registers.index')->with('success', 'Register updated successfully.');
    }

    public function destroy(Register $register): RedirectResponse
    {
        $this->authorize('delete', $register);
        $this->ensureBranchAccess($register);
        $register->delete();

        return redirect()->route('registers.index')->with('success', 'Register moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Register::class);
        $registers = Register::onlyTrashed()->with('branch')->paginate(15);

        return view('registers.trash', compact('registers'));
    }

    public function restore(int $register): RedirectResponse
    {
        $register = Register::onlyTrashed()->findOrFail($register);
        $this->authorize('restore', $register);
        $this->ensureBranchAccess($register);
        $register->restore();

        return redirect()->route('registers.trash')->with('success', 'Register restored successfully.');
    }

    public function forceDelete(int $register): RedirectResponse
    {
        $register = Register::onlyTrashed()->findOrFail($register);
        $this->authorize('forceDelete', $register);
        $this->ensureBranchAccess($register);
        $register->forceDelete();

        return redirect()->route('registers.trash')->with('success', 'Register permanently deleted.');
    }
}
