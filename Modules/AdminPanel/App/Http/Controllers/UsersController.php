<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserManagement\App\Models\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['doctorProfile.specialization', 'patientProfile']);

        $users = $query->latest()->get();

        return view('adminDashboard.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
