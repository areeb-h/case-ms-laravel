<?php

namespace App\Http\Controllers;

use App\Mail\NewUserCredentials;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->input('search_query');
            $role = $request->input('role');
            $status = $request->input('status');

            // Build the query based on the filters
            $users = User::query();

            if ($query) {
                $users->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            }

            if ($role) {
                $users->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            }

            if ($status !== null) {
                $users->where('is_active', $status);
            }

            $users = $users->get();
            return view('partials.user_rows', ['users' => $users]);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function profile()
    {
        $user = auth()->user();
        return response()->json($user);
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        // Retrieve the user by ID
        $user = User::findOrFail($id);

        // Update user attributes
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Save the changes
        $user->save();

        // Update the role; Assuming role names match what you get from the form
        $newRoleName = $request->input('role');
        if ($newRoleName) {
            // Remove all roles the user currently has
            foreach ($user->roles as $role) {
                $user->removeRole($role->name);
            }

            // Add the new role
            $user->assignRole($newRoleName);
        }

        // Redirect or return a response
        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => 1]);
        return redirect()->back()->with('status', 'User activated successfully');
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => 0]);
        return redirect()->back()->with('status', 'User deactivated successfully');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => 'required|array',
        ]);
    }

    /*public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);*/

        /*if (auth()->user()->hasRole('Administrator')) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($validated['roles']);

            $message = 'User created successfully.';
            $redirectRoute = 'admin.dashboard';
        } else {
            $message = 'You do not have permission to create users.';
            $redirectRoute = 'admin.dashboard';
        }

        return redirect()->route($redirectRoute)->with('message', $message);*/
        /*$randomPassword = Str::random(8);

        if (auth()->user()->hasRole('Administrator')) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($randomPassword),
            ]);

            $user->assignRole($validated['roles']);

            // Send the credentials to the new user
            Mail::to($user->email)->send(new NewUserCredentials($user, $randomPassword));

            $message = 'User created successfully.';
            $redirectRoute = 'admin.dashboard';
        } else {
            $message = 'You do not have permission to create users.';
            $redirectRoute = 'admin.dashboard';
        }

        return redirect()->route($redirectRoute)->with('success', $message);
    }*/

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required|array',
        ]);

        $randomPassword = Str::random(8);  // Generate a random password

        if (auth()->user()->hasRole('Administrator')) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($randomPassword),
            ]);

            $user->assignRole($validated['roles']);

            // Send the email with the credentials
            Mail::to($user->email)->send(new NewUserCredentials($user, $randomPassword));

            $message = 'User created successfully.';
            $redirectRoute = 'admin.users';
        } else {
            $message = 'You do not have permission to create users.';
            $redirectRoute = 'admin.users';
        }

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    public function destroy($id)
    {
        // Check if the current user has permission to delete users
        if (auth()->user()->hasRole('Administrator')) {
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            return redirect()->route('admin.users')->with('success', 'User deleted successfully');
        } else {
            return redirect()->route('admin.users')->with('error', 'You do not have permission to delete users');
        }
    }

}
