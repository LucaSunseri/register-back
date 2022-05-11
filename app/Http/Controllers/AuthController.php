<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {

        $user = User::create([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'),),
        ]);

        return new UserResource($user);
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

        return new UserResource($user);
    }
}
