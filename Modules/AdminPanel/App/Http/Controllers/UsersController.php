<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserManagement\App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of all users with their roles and information
     */
    public function index(Request $request)
    {
        $query = User::with(['doctorProfile.specialization', 'patientProfile']);

        $users = $query->latest()->get();

        return view('adminDashboard.users.index', compact('users'));
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Prevent deletion of admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    // Existing static view methods for backward compatibility
    public function codeGenerator()
    {
        return view('aiapplication/codeGenerator');
    }

    public function addUser()
    {
        return view('users/addUser');
    }

    public function usersGrid()
    {
        return view('users/usersGrid');
    }

    public function usersList()
    {
        return view('users/usersList');
    }

    public function viewProfile()
    {
        return view('users/viewProfile');
    }
}
