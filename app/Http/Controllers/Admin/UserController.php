<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);
        $users = User::query()
            ->when($filters['search'] ?? null, fn ($query, string $search) => $query
                ->where(fn ($query) => $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('student_id', 'like', '%'.$search.'%')))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', ['users' => $users, 'filters' => $filters]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_USER, User::ROLE_ADMIN])],
        ]);

        if ($request->user()->is($user) && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->withErrors(['role' => 'You cannot remove your own admin access.']);
        }

        $user->forceFill(['role' => $validated['role']])->save();

        return redirect()->route('admin.users.index')->with('status', 'User role updated.');
    }
}
