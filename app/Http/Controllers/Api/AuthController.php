<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
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
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input

        $registrationData['password'] = bcrypt($request->password); // enkripsi password
        $user = User::create($registrationData); // membuat user baru
        event(new Registered($user));
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); //return data user dalam bentuk json
    }

    // show data user tertentu
    public function show($id)
    {
        $users = User::find($id); //mencari course berdasarkan data id

        if (!is_null($users)) {
            return response([
                'message' => 'Retrive User Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404);
    }

    //login
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

    //update data user
    public function update(Request $request, $id){
        $users = User::find($id);
        if(is_null($users)){
            return response([
                'message'=>'User Not Found',
                'data'=>null
            ],400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required|max:60',
            'email' => ['required','email',Rule::unique('users')->ignore($users)],
            'gender' => 'required',
            'dateborn' => 'required|date_format:Y-m-d',
            'username' => ['required',Rule::unique('users')->ignore($users)],
            'password' => 'required'
        ]); // validasi data

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $users->name = $updateData['name'];
        $users->email = $updateData['email'];
        $users->email = $updateData['gender'];
        $users->email = $updateData['dateborn'];
        $users->email = $updateData['username'];
        $users->password = bcrypt($updateData['password']);;

        if($users->save()){
            return response([
                'message'=> 'Update User Success',
                'data'=>$users
            ],200);
        }
        return response([
            'message'=>'Update User Failed',
            'data'=>null
        ],400);
    }

    //    method untuk menghapus user tertentu
    public function destroy($id)
    {
        $users = User::find($id);

        if(is_null($users)){
            return response([
                'message'=>'User Not Found',
                'data'=>null
            ],400);
        }//return message saat database tidak ditemukan

        if($users->delete()){
            return response([
                'message'=>'Delete User Success',
                'data'=>$users
            ],200);
        }

        return response([
            'message'=>'Delete user Failed',
            'data'=>null
        ],400);
    }

}
