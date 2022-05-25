<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {

        $new_user = new User();

        $new_user->fill($request->all());
        $new_user->password = bcrypt($request->get('password'));
        
        $new_user->assignRole('developer');

        $new_user->save();

        return new UserResource($new_user);
    }

    public function login(LoginRequest $request)
    {

        // Controllo 
        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return response([
                'message' => 'Credenziali non valide'
            ], 401);
        }

        // dd($user->getAllPermissions());

        return new UserResource($user);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response('Logout avvenuto con successo!');
    }
}
