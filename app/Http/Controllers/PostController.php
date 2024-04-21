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
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Lista todos os posts ou filtra por tag",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Filtrar por tag",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Lista de posts retornada com sucesso"),
     *     @OA\Response(response="401", description="Não autorizado"),
     * )
     */
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
        //return view('posts.create');
    }
/**
 * Store a newly created resource in storage.
 *
 * @OA\Post(
 *     path="/api/posts",
 *     summary="Cria uma nova postagem",
 *     tags={"Posts"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", maxLength=150, example="Título da postagem"),
 *             @OA\Property(property="content", type="string", example="Conteúdo da postagem"),
 *             @OA\Property(property="author", type="string", maxLength=100, example="Autor da postagem"),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"tag1", "tag2"})
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Postagem criada com sucesso",
 *         
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação"
 *     )
 * )
 */

    public function store(CreatePostRequest  $request)
    {
        
        $post = Post::create($request->all());
        return response()->json($post, 201);

    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Exibe um post específico",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Post retornado com sucesso"),
     *     @OA\Response(response="401", description="Não autorizado"),
     *     @OA\Response(response="404", description="Post não encontrado"),
     *  security={{ "bearerAuth": {} }},
     * )
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }

    
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Atualiza um post existente",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *                                       
     *     @OA\Response(response="200", description="Post atualizado com sucesso"),
     *     @OA\Response(response="401", description="Não autorizado"),
     *     @OA\Response(response="404", description="Post não encontrado"),
     * )
     */
    public function update(UpdatePostRequest $request, string $id)
    {

        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json($post, 200);
    }

       /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Exclui um post existente",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="204", description="Post excluído com sucesso"),
     *     @OA\Response(response="401", description="Não autorizado"),
     *     @OA\Response(response="404", description="Post não encontrado"),
     * )
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted'], 204);
    }
}
