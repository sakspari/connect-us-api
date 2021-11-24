<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'gender' => 'required',
            'dateborn' => 'required|date_format:Y-m-d',
            'email' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input

        $registrationData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($registrationData); // membuat user baru
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); //return data user dalam bentuk json
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input

        if (!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401); // return error gagal login

        $user = Auth::User();
        $token = $user->createToken('Authentication Token')->accessToken; // generate token

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); // return data user dan token dalam bentuk json
    }
}
