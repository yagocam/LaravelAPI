<?php
// tests/Feature/PostControllerTest.php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Post;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        
         $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        
        $response = $this->postJson('/api/login', [
            'email' => 'test@gmail.com',
            'password' => '12345678',
        ]);

        $this->token = $response['access_token'];
    }


    /** @test */
    public function it_returns_a_list_of_posts()
{
    // Criando alguns posts de exemplo
    $posts = [
        Post::create([
            'title' => 'First Post',
            'author' => 'John Doe',
            'content' => 'This is the first post content.',
            'tags' => ['example', 'first']
        ]),
        Post::create([
            'title' => 'Second Post',
            'author' => 'Jane Smith',
            'content' => 'This is the second post content.',
            'tags' => ['example', 'second']
        ]),
        Post::create([
            'title' => 'Third Post',
            'author' => 'Alice Johnson',
            'content' => 'This is the third post content.',
            'tags' => ['example', 'third']
        ])
    ];
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json',
    ])->getJson('/api/posts');

    // Verificando o código de status da resposta
    $response->assertStatus(200);

    // Verificando se o conteúdo da resposta contém os posts criados
    foreach ($posts as $post) {
        $response->assertJsonFragment([
            'title' => $post->title,
            'author' => $post->author,
            'content' => $post->content,
            'tags' => $post->tags
        ]);
    }
}
 /** @test */
 public function it_returns_posts_with_specified_tag()
 {
     $tag = 'example';
     $postWithTag = Post::create([
         'title' => 'Post with Tag',
         'author' => 'Tag Tester',
         'content' => 'This is a post with the specified tag.',
         'tags' => [$tag]
     ]);

     $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json',
    ])->getJson("/api/posts?tag={$tag}");

     $response->assertStatus(200);

     $response->assertJsonFragment([
         'title' => $postWithTag->title,
         'author' => $postWithTag->author,
         'content' => $postWithTag->content,
         'tags' => $postWithTag->tags
     ]);
 }
 /** @test */
 public function it_creates_a_new_post()
 {
     // Dados do novo post a ser criado
     $postData = [
         'title' => 'New Post',
         'author' => 'Test Author',
         'content' => 'This is the content of the new post.',
         'tags' => ['test', 'example']
     ];
     $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json',
    ])->postJson('/api/posts',$postData);
    
     $response->assertStatus(201);

     $response->assertJsonFragment($postData);
 }

 /** @test */
 public function it_updates_an_existing_post()
 {
    $post = Post::create([
        'title' => 'Original Post Title',
        'author' => 'Original Author Name',
        'content' => 'Original post content.',
        'tags' => ['original', 'tags']
    ]);

     // Novos dados para a postagem
     $newData = [
         'title' => 'Updated Post Title',
         'author' => 'Updated Author Name',
         'content' => 'Updated post content.',
         'tags' => ['updated', 'tags']
     ];
     $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json',
    ])->putJson("/api/posts/$post->id",$newData);

     $response->assertStatus(200);
 }

  /** @test */
  public function it_deletes_a_post_by_id()
  {
      // Criando uma postagem de exemplo
      $post = Post::create([
        'title' => 'Original Post Title',
        'author' => 'Original Author Name',
        'content' => 'Original post content.',
        'tags' => ['original', 'tags']
    ]);
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        'Accept' => 'application/json',
    ])->deleteJson("/api/posts/{$post->id}");

      // Verificando se a postagem foi excluída corretamente
      $response->assertStatus(204);
  }
}
