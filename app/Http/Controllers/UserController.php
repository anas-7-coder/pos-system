<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->with(['roles', 'branches'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::query()
            ->where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        $branches = Branch::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('users.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'confirmed', 'min:8'],
            'role_id'      => ['required', 'integer', 'exists:roles,id'],
            'status'       => ['required', Rule::in(['Active', 'Inactive'])],
            'branch_ids'   => ['nullable', 'array'],
            'branch_ids.*' => ['integer', 'exists:branches,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => $validated['password'],
                'status'   => $validated['status'],
            ]);

            $user->roles()->sync([$validated['role_id']]);
            $user->branches()->sync($validated['branch_ids'] ?? []);
        });

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'branches', 'employee']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::query()
            ->where('status', 'Active')
            ->orderBy('display_name')
            ->get();

        $branches = Branch::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $user->load(['roles', 'branches']);

        return view('users.edit', compact('user', 'roles', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'     => ['nullable', 'confirmed', 'min:8'],
            'role_id'      => ['required', 'integer', 'exists:roles,id'],
            'status'       => ['required', Rule::in(['Active', 'Inactive'])],
            'branch_ids'   => ['nullable', 'array'],
            'branch_ids.*' => ['integer', 'exists:branches,id'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $data = [
                'name'   => $validated['name'],
                'email'  => $validated['email'],
                'status' => $validated['status'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = $validated['password'];
            }

            $user->update($data);
            $user->roles()->sync([$validated['role_id']]);
            $user->branches()->sync($validated['branch_ids'] ?? []);
        });

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(int $userId)
    {
        $user          = User::query()->findOrFail($userId);
        $currentUserId = Auth::user()?->getAuthIdentifier();

        if ((int) $user->getKey() === (int) $currentUserId) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User moved to trash.');
    }

    public function trash()
    {
        $users = User::onlyTrashed()
            ->with(['roles', 'branches'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('users.trash', compact('users'));
    }

    public function restore(int $user)
    {
        $user = User::onlyTrashed()->findOrFail($user);
        $user->restore();

        return redirect()->route('users.trash')->with('success', 'User restored successfully.');
    }

    public function forceDelete(int $userId)
    {
        $user          = User::onlyTrashed()->findOrFail($userId);
        $currentUserId = Auth::user()?->getAuthIdentifier();

        if ((int) $user->getKey() === (int) $currentUserId) {
            return back()->with('error', 'You cannot permanently delete your own account.');
        }

        $user->roles()->detach();
        $user->branches()->detach();

        $user->forceDelete();

        return redirect()->route('users.trash')->with('success', 'User permanently deleted.');
    }
}