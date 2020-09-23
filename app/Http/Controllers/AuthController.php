<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function register(Request $request)
    {
        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            
            ]);

            $token = auth()->login($user);

            return $this->respondWithToken($token);
    }


    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if(!$token = Auth::attempt($credentials)){

            return response()->json(['error' => 'unauthorized'], 401);

        }
        
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}
