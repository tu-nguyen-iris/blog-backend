<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;    

class ApiRegister extends Controller
{
    //
    public function register(ApiRegisterRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = Hash::make($request->password);
        $user->role = 1;
        $user->save();
        return response()->json('success', 200);
    }

    public function login(ApiLoginRequest $request)
    {
        if (Auth::attempt(array(
            'email' => $request->email,
            'password' => $request->password
        ))) {
            $user = User::whereEmail($request->email)->first();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            $user->token = $tokenResult->accessToken;
            return response()->json($user, 200);
        } else return response()->json(['msg' => 'Sai ten truy cap'], 200);
    }
}
