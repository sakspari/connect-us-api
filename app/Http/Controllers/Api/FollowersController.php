<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Followers;


class FollowersController extends Controller
{
    public function show($id)
    {
        $followers = Followers::where('user_id_1', '=' , $id)->get();
        
        if (!is_null($followers)) {
            return response([
                'message' => 'Retrieve All Followers Success',
                'data' => $followers
            ], 200);
        }

        return response([
            'message' => 'Followers not found',
            'data' => null
        ], 404);
    }

    public function find($id)
    {
        $follower = Followers::where('user_id_2', '=' , $id)->get();
        
        if (!is_null($follower)) {
            return response([
                'message' => 'Retrieve Follower Success',
                'data' => $follower
            ], 200);
        }

        return response([
            'message' => 'Followers not found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'user_id_1' => 'required|numeric',
            'user_id_2' => 'required|numeric'
        ]);

        if ($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $follower = Followers::create($storeData);
        return response([
            'message' => 'Add Follower Success',
            'data' => $follower
        ], 200);
    }

    public function destroy($id)
    {
        $follower = Followers::find($id);
        
        if (is_null($follower)) {
            return response([
                'message' => 'Follower Not Found',
                'data' => null
            ], 404);
        }

        if($follower->delete()) {
            return response([
                'message' => 'Delete Follower Success',
                'data' => $follower
            ], 200);
        }

        return response([
            'message' => 'Delete Follower Failed',
            'data' => null,
        ], 400);
    }

}
