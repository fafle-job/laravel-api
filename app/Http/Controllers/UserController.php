<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller {

    public function create(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();

        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 201);
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->route('id'),
            'password' => 'required',
        ]);

        $user = User::find($request->route('id'));
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 201);

    }

    public function status(Request $request) {

        $user = User::find($request->route('id'));
        $user->status != $user->status;
        $user->save();

        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 201);

    }



    public function delete(Request $request) {
        User::find($request->route('id'))->delete();

        return response()->json([
            'message' => 'success'
        ], 201);
    }

    public function singup(Request $request) {

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();
        return response()->json([
                    'message' => 'success'
                        ], 201);
    }

    public function singin(Request $request) {
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

    public function getUsers() {

        $tasks = User::orderBy('created_at', 'DESC')->get();
        $response = [
            'users' => $tasks
        ];
        return response()->json($response, 200);
    }


}
