<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::paginate(5);
        return response()->json([
            'status' => 1,
            'message' => 'post facted',
            'data' => $post
        ], 422);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'data' => $validator->errors()->all()
            ], 422);
        }

        $post = Post::create([
            "title" => $request->title,
            "body" => $request->body,
        ]);

         return response()->json([
                'status' => 1,
                'message' => 'Post created successfully',
                'data' => $post,
            ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        return response()->json([
            'status' => 1,
            'message' => 'Post return',
            'data' => $post,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'data' => $validator->errors()->all()
            ], 422);
        }

        $post = Post::find($id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

         return response()->json([
            'status' => 1,
            'message' => 'Post updated successfully',
            'data' => $post,
        ], 201);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        $post->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Post delete successfully',
            'data' => null,
        ], 201);
    }
}