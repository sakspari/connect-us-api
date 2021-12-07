<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Comment;
use App\Models\User;

class CommentController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $comment = Comment::all();  //mengambil semua data comment
        $comment = Comment::join('users', 'users.id', '=', 'comments.user_id')->select('name','comments.id AS id', 'user_id', 'post_id', 'content')->get();

        if (count($comment) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $comment
            ], 200);
        }  // return data semua course dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);  // return message data course kosong
    }

    // method untuk menampilkan 1 data course (search)
    public function show($id)
    {
        $comment = Comment::find($id);  // mencari data course berdasarkan id

        if (!is_null($comment)) {
            return response([
                'message' => 'Retrieve Comment Success',
                'data' => $comment
            ], 200);
        }  // return data course yagn ditemukan dalam bentuk json

        return response([
            'message' => 'Comment Not Found',
            'data' => null
        ], 404);  // return message saat data course tidak ditemukan
    }

    public function showInPost($post_id)
    {
        $comment = Comment::where('post_id', $post_id)->get();  // mengambil semua data berdasar post_id

        if (!is_null($comment)) {
            return response([
                'message' => 'Retrieve Comment Success',
                'data' => $comment
            ], 200);
        }  // return data course yagn ditemukan dalam bentuk json

        return response([
            'message' => 'Comment Not Found',
            'data' => null
        ], 404);  // return message saat data course tidak ditemukan
    }

    // method untuk menambah 1 data course baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all();  // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'content' => 'required',
            'post_id' => 'required',
            'user_id' => 'required'
        ]);  // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $comment = Comment::create($storeData);
        return response([
            'message' => 'Add Comment Success',
            'data' => $comment
        ], 200); // return data course baru dalam bentuk json
    }

    public function storeInPost(Request $request, $post_id, $user_id)
    {
        $storeData = $request->all();  // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'content' => 'required'
        ]);  // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        // data post_id sama user_id dapet dari view Post
        $storeData['post_id'] = $post_id;
        $storeData['user_id'] = $user_id;
        $comment = Comment::create($storeData);
        return response([
            'message' => 'Add Comment in Post Success',
            'data' => $comment
        ], 200); // return data course baru dalam bentuk json
    }

    // method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $comment = Comment::find($id); // mencari data product berdasarkan id

        if (is_null($comment)) {
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        } // return message saat data course tidak ditemukan

        if ($comment->delete()) {
            return response([
                'message' => 'Delete Comment Success',
                'data' => $comment
            ], 200);
        } // return messsage saat berhasil menghapus data course

        return response([
            'message' => 'Delete Comment Failed',
            'data' => null
        ], 400);  // return message saat gagal menghapus data course
    }

    public function destroyPost($idPost)
    {
        $comment = Comment::where('post_id',$idPost); // mencari data product berdasarkan id

        if (is_null($comment)) {
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        } // return message saat data course tidak ditemukan

        if ($comment->delete()) {
            return response([
                'message' => 'Delete Comment Post Success',
                'data' => $comment
            ], 200);
        } // return messsage saat berhasil menghapus data course

        return response([
            'message' => 'Delete Comment Failed',
            'data' => null
        ], 400);  // return message saat gagal menghapus data course
    }
    
    // method untuk mengubah data 1 course (update)
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);  // mencari data course berdasar id
        if (is_null($comment)) {
            return response([
                'message' => 'Comment Not Found',
                'data' => null
            ], 404);
        }  // Return message saat data course tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'content' => 'required'
        ]);  // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $comment->content = $updateData['content']; //edit content

        if ($comment->save()) {
            return response([
                'message' => 'Update Comment Succes',
                'data' => $comment
            ], 200);
        } // return data course yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update Comment Failed',
            'data' => null
        ], 400); // return message saat course gagal di edit
    }
}
