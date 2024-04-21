<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

trait ResponseTrait
{
    protected function jsonResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }
}
trait PostCRUDTrait
{
    use ResponseTrait;
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
    }

    public function create()
    {
        // Implementação opcional para o método create
    }

    public function store(CreatePostRequest $request)
    {
        $post = Post::create($request->all());
        return $this->jsonResponse($post, 201);
    }

    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }

    public function edit(string $id)
    {
        // Implementação opcional para o método edit
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return $this->jsonResponse($post, 200);
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return $this->jsonResponse(['message' => 'Post deleted'], 204);
    }
}
class PostController extends Controller
{
   use PostCRUDTrait;
}
