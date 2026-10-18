<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:admin,rental_manager,sales_manager,customer_support,customer'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'driver_license_number' => ['nullable', 'string', 'max:50'],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'driver_license_number' => $validated['driver_license_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        ActivityLog::log('USER_CREATED', "New user {$user->name} ({$user->email}) created with role {$user->role}.", $user, Auth::user());

        return redirect()->route('admin.users.index')->with('success', "User {$user->name} created successfully.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,rental_manager,sales_manager,customer_support,customer'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'driver_license_number' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,suspended'],
            'password' => ['nullable', Password::defaults()],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'driver_license_number' => $validated['driver_license_number'] ?? null,
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::log('USER_UPDATED', "User {$user->name} profile/role updated.", $user, Auth::user());

        return redirect()->route('admin.users.index')->with('success', "User {$user->name} updated successfully.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot suspend your own account.']);
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        ActivityLog::log('USER_STATUS_TOGGLED', "User {$user->name} status changed to {$newStatus}.", $user, Auth::user());

        return back()->with('success', "User {$user->name} is now {$newStatus}.");
    }
}
