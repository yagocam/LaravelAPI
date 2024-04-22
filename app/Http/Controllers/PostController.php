<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Repositories\Contracts\PostRepositoryInterface;
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
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        if ($request->has('tag')) {
            $tag = $request->tag;
            $posts = $this->postRepository->findByTag($tag);
        } else {
            $posts = $this->postRepository->all();
        }
        return PostResource::collection($posts);
    }

    public function create()
    {
        //
    }

    public function store(CreatePostRequest $request)
    {
        $post = $this->postRepository->create($request->all());
        return $this->jsonResponse($post, 201);
    }

    public function show(string $id)
    {
        $post = $this->postRepository->find($id);
        return new PostResource($post);
    }

    public function edit(string $id)
    {
        // Implementação opcional para o método edit
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        $post = $this->postRepository->update($id, $request->all());
        return $this->jsonResponse($post, 200);
    }

    public function destroy(string $id)
    {
        $this->postRepository->delete($id);
        return $this->jsonResponse(['message' => 'Post deleted'], 204);
    }
}
class PostController extends Controller
{
   use PostCRUDTrait;
}
