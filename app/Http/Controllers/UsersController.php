<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * Retrieves a paginated list of users and passes it to the 'users.index' view.
     */
    public function index()
    {
        // Get a paginated list of users (20 per page)
        $users = User::paginate(20);
        
        // Return the users index view with the list of users
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Returns the view for creating a new user.
     */
    public function create()
    {
        // Return the view to create a new user
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     * Validates the request, hashes the password, creates a new user, and associates a media file (photo) with the user.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $sanitized = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'user_role' => 'required',
            'photo' => 'required|image',
            'description' => 'nullable',
            'cost_per_month' => 'nullable|numeric',
            'experience' => 'nullable'
        ]);

        // Hash the password before storing it
        $sanitized['password'] = Hash::make($sanitized['password']);

        // Create a new user with the sanitized data
        $user = User::create($sanitized);

        // Associate the photo with the user
        $user->addMedia($sanitized['photo'])->toMediaCollection();

        // Redirect to the users index page
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     * (Currently not implemented)
     */
    public function show(string $id)
    {
        // Placeholder for displaying a specific user
    }

    /**
     * Show the form for editing the specified resource.
     * Returns the view for editing an existing user.
     */
    public function edit(User $user)
    {
        // Return the view to edit an existing user
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * Validates the request, updates the user's data, hashes the password if provided, and updates the user's media (photo) if necessary.
     */
    public function update(Request $request, User $user)
    {
        // Validate the incoming request data
        $sanitized = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'user_role' => 'required',
            'description' => 'nullable',
            'cost_per_month' => 'nullable|numeric',
            'experience' => 'nullable'
        ]);

        // Hash the password if it is provided and not null
        if ($request->filled('password')) {
            $sanitized['password'] = Hash::make($request->password);
        }

        // Update the user's data with the sanitized data
        $user->update($sanitized);

        // Update the user's media (photo) if a new one is provided
        if ($request->hasFile('photo')) {
            $user->clearMediaCollection();
            $user->addMedia($request->file('photo'))->toMediaCollection();
        }

        // Redirect to the users index page
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     * Deletes a user and redirects to the users index page.
     */
    public function destroy(User $user)
    {
        // Delete the user
        $user->delete();
        
        // Redirect to the users index page
        return redirect()->route('users.index');
    }
}
