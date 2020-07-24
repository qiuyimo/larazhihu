<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_view_questions()
    {
        // 0. 抛出异常.
        $this->withoutExceptionHandling();

        // 1. 假设 /questions 路由存在

        // 2. 访问链接 /questions
        $testRes = $this->get('/questions');

        // 3. 正常返回 200
        $testRes->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_can_view_a_published_question()
    {
        // 1. 创建一个问题
        $question = factory(Question::class)->create(['published_at' => Carbon::parse('-1 week')]);

        // 2. 访问链接
        $test = $this->get('/questions/' . $question->id);

        // 3. 应该看到问题的内容
        $test->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /**
     * @test
     */
    public function user_cannot_view_unpublished_question()
    {
        $question = factory(Question::class)->create(['published_at' => null]);

        $this->withExceptionHandling()->get('/questions/' . $question->id)->assertStatus(404);
    }
}
