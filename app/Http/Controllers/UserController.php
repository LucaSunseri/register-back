<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserController extends Controller
{
    public function getAllDeveloperUser() {
        $developers = User::whereHas('roles', function(Builder $query) {
            $query->where('name', 'developer');
        })->get();

        return response()->json($developers);
    }
}