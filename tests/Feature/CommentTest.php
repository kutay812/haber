<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create default roles
        Role::create(['name' => 'User']);
    }

    /**
     * Guest user cannot comment
     */
    public function test_guest_cannot_comment_on_news(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $category = Category::factory()->create();
        $news = News::factory()->create(['category_id' => $category->id]);

        $response = $this->post(route('comments.store', $news->id), [
            'content' => 'This is a test comment content',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', [
            'content' => 'This is a test comment content',
        ]);
    }

    /**
     * Authed user can comment
     */
    public function test_authenticated_user_can_comment_on_news(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $news = News::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->post(route('comments.store', $news->id), [
            'content' => 'This is a test comment content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment content',
            'user_id' => $user->id,
            'news_id' => $news->id,
        ]);
    }

    /**
     * User cannot edit other users' comment
     */
    public function test_user_cannot_update_other_users_comment(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $category = Category::factory()->create();
        $news = News::factory()->create(['category_id' => $category->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user1->id,
            'news_id' => $news->id,
            'content' => 'Old comment',
        ]);

        $response = $this->actingAs($user2)->put(route('comments.update', $comment->id), [
            'content' => 'Malicious update',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', [
            'id'      => $comment->id,
            'content' => 'Old comment',
        ]);
    }
}
