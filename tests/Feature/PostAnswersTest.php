<?php

namespace Tests\Feature;

use App\User;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function signed_in_user_can_post_an_answer_to_a_published_question()
    {
        // 1. 假设存在某个问题
        $question = factory(Question::class)->state('published')->create();
        $this->actingAs($user = factory(User::class)->create());

        // 2. 触发某个路由
        $response = $this->post("/questions/{$question->id}/answers", [
            'content' => 'This is a answer.',
        ]);

        // 3. 看到预期结果
        $response->assertStatus(201);

        $answer = $question->answers()->where('user_id', $user->id)->first();
        $this->assertNotNull($answer);
        $this->assertEquals(1, $question->answers()->count());
    }

    /**
     * @test
     */
    public function can_not_post_an_answer_to_a_unpublished_question()
    {
        $question = factory(Question::class)->state('unpublished')->create();
        $user = factory(User::class)->create();

        $response = $this->withExceptionHandling()
            ->post('/questions/' . $question->id . '/answers', [
                'user_id' => $user->id,
                'content' => 'This is a answer.',
            ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('answers', ['question_id' => $question->id]);
        $this->assertEquals(0, $question->answers()->count());
    }

    /**
     * @test
     */
    public function content_is_required_to_post_answers()
    {
        $this->withExceptionHandling();

        $question = factory(Question::class)->state('published')->create();
        $user = factory(User::class)->create();

        $response = $this->post("/questions/{$question->id}/answers", [
            'user_id' => $user->id,
            'content' => null
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('content');
    }
}
