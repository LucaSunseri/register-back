<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create(Request $request) {

        dd($request);

        Role::create(['name' => $request->nsme]);

        return response('ruolo creato');
    }
}
