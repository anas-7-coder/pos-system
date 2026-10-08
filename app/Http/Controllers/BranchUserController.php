<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignBranchUserRequest;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchUserController extends Controller
{
    public function index(Branch $branch): View
    {
        $this->authorize('viewAny', User::class);
        $users = $branch->users()->with('employee')->orderBy('name')->paginate(15);

        return view('branches.users.index', compact('branch', 'users'));
    }

    public function create(Branch $branch): View
    {
        $this->authorize('assign', User::class);
        $users = User::query()->where('status', 'active')->whereDoesntHave('branches', function ($query) use ($branch) {
            $query->where('branches.id', $branch->id);
        })
            ->orderBy('name')
            ->get();

        return view('branches.users.create', compact('branch', 'users'));
    }

    public function store(AssignBranchUserRequest $request, Branch $branch): RedirectResponse
    {
        $this->authorize('assign', User::class);
        $user = User::findOrFail($request->validated('user_id'));
        abort_unless($user->status === 'Active', 422, 'Inactive users cannot be assigned to a branch.');
        $branch->users()->syncWithoutDetaching([$user->id]);

        return redirect()->route('branches.users.index', $branch)->with('success', 'User assigned to branch successfully.');
    }

    public function destroy(Branch $branch, User $user): RedirectResponse
    {
        $this->authorize('remove', User::class);
        $branch->users()->detach($user->id);

        return redirect()->route('branches.users.index', $branch)->with('success', 'User removed from branch successfully.');
    }
}
