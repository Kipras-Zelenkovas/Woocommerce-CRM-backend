<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller
{

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'     => 'string|email:rfc,dns|max:40|required',
                'password'  => 'string|min:8|max:40|required'
            ]);

            if (FacadesAuth::attempt($credentials)) {
                $request->session()->regenerate();


                return response()->json([
                    'message'   => 'User successfully loged in',
                    'token'     => $request->user()->createToken("API TOKEN")->plainTextToken,
                ]);
            } else {
                return response()->json([
                    'error' => 'Wrong credentials'
                ], 402);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'      => 'string|max:25|required',
                'email'     => 'string|max:40|email:rfc,dns|required',
                'password'  => 'string|min:8|max:40|required'
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            $user->save();

            return response()->json([
                'message'   => 'User successfully created'
            ]);

            // return response()->json($validated);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            $request->session()->invalidate();

            FacadesAuth::guard('web')->logout();

            return response()->json([
                'message'   => 'User successfully logout'
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
