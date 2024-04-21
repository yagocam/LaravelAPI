<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        if ($request->has('tag')) {
            $tag = $request->tag;
            $posts = Post::whereJsonContains('tags', $tag)->get();
        } else {
            $posts = Post::all();
        }
        return PostResource::collection($posts);
        //return view("posts.index", compact("posts"));
    }

    
    public function create()
    {
        //return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest  $request)
    {
        
        $post = Post::create($request->all());
        return response()->json($post, 201);

        //return redirect()->route('posts.index')->with('success', 'Post created');
    }

   
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
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
    public function update(UpdatePostRequest $request, string $id)
    {

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
