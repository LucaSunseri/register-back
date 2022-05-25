<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function create(Request $request) {

        $new_permission = Permission::create(['name' => $request->name]);

        return response()->json($new_permission);
    }

    public function assign(Request $request) {

        $permission = Permission::find($request->permission_id);
        $user = User::find($request->user_id);

        $user->givePermissionTo($permission);

        return response()->json('permesso assegnato');

    }

    public function remove(Request $request) {

        $permission = Permission::find($request->permission_id);
        $user = User::find($request->user_id);

        $user->revokePermissionTo($permission);

        return response()->json('permesso rimosso');

    }
}
