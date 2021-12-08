<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;


class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = DB::table('feedback')
            ->join('users', 'users.id', '=', 'feedback.user_id')
            ->select('feedback.id AS id', 'feedback_content', 'feedback_star', 'name')
            ->get();
        
        if (!is_null($feedbacks)) {
            return response([
                'message' => 'Retrieve All feedbacks Success',
                'data' => $feedbacks
            ], 200);
        }

        return response([
            'message' => 'feedback not found',
            'data' => null
        ], 404);
    }
    
    public function show($id)
    {
        $feedbacks = Feedback::where('user_id', '=' , $id)->first();
        
        if (!is_null($feedbacks)) {
            return response([
                'message' => 'Retrieve feedback Success',
                'data' => $feedbacks
            ], 200);
        }

        return response([
            'message' => 'feedback not found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'feedback_content' => 'required',
            'feedback_star' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);

        if ($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $feedback = Feedback::create($storeData);
        return response([
            'message' => 'Create Feedback Success',
            'data' => $feedback
        ], 200);
    }

    public function destroy($id)
    {
        $feedback = Feedback::where('user_id', '=' , $id)->first();
        
        if (is_null($feedback)) {
            return response([
                'message' => 'Feedback Not Found',
                'data' => null
            ], 404);
        }

        if($feedback->delete()) {
            return response([
                'message' => 'Delete feedback Success',
                'data' => $feedback
            ], 200);
        }

        return response([
            'message' => 'Delete feedback Failed',
            'data' => null,
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::where('user_id', '=' , $id)->first();
        
        if (is_null($feedback))
        {
            return response([
                'message' => 'feedback Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'feedback_content' => 'required',
            'feedback_star' => 'required|numeric'
        ]);

        if ($validate->fails())
        { 
            return response(['message' => $validate->errors()], 400);
        }

        $feedback->feedback_content = $updateData['feedback_content'];
        $feedback->feedback_star = $updateData['feedback_star'];

        if ($feedback->save()) {
            return response([
                'message' => 'Update feedback Success',
                'data' => $feedback
            ], 200);
        }

        return response([
            'message' => 'Update feedback Failed',
            'data' => null,
        ], 400);
    }
}
