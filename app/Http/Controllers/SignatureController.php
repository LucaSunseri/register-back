<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function checkSignature(Request $request) {

        $signature =  $request->user()->signature;

        return response()->json(!is_null($signature));
    }

    public function saveSignature(Request $request) {

        $user = $request->user();

        $user->update($request->all());
        $user->refresh();

        return response($user);
    }
}
