<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:companies|unique:users',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'password' => 'required',
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();

        $company = new Company([
            'name' => $request->input('name'),
            'owner' => $user->id,
            'address' => $request->input('address')
        ]);
        $company->save();

        $user->company_id = $company->id;
        $user->save();

        return response()->json([
            'message' => 'success'
        ], 201);
    }


    public function auth(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'invalid data'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Unable to create token'
            ], 500);
        }

        return response()->json([
            'token' => $token
        ], 200);
    }

}
