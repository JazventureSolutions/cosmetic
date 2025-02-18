<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class RoleController extends Controller
{
    public function assignRole(Request $request, User $user)
    {
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role);
        }
        return redirect()->back()->with('success', 'Role assigned successfully!');
    }
}