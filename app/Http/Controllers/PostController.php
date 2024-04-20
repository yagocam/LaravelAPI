<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
        //return view("posts.index", compact("posts"));
    }

    
    public function create()
    {
        //return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:20',
            'tags' => 'required|array',
            'content' => 'required|string',
            'author' => 'required|string|max:50'
        ]);
        $post = Post::create($request->all());
        return response()->json($post, 201);

        //return redirect()->route('posts.index')->with('success', 'Post created');
    }

   
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post, 201);
        //return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post, 201);
        //return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:20',
            'tags' => 'required|array',
            'content' => 'required|string',
            'author' => 'required|string|max:50'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json($post, 200);
        //return redirect()->route('posts.index')->with('success', 'Post updated !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted'], 204);
        //return redirect(route('posts.index'))->with('success','Post deleted!');
    }
}
