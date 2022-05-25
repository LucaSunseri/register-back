<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create(Request $request) {

        $new_role = Role::create(['name' => $request->name]);

        return response()->json($new_role);
    }

    public function assign(Request $request) {

        $role = Role::find($request->role_id);
        $user = User::find($request->user_id);

        $user->assignRole($role);

        return response()->json('ruolo assegnato');

    }

    public function remove(Request $request) {

        $role = Role::find($request->role_id);
        $user = User::find($request->user_id);

        $user->removeRole($role);

        return response()->json('ruolo rimosso');

    }
}
