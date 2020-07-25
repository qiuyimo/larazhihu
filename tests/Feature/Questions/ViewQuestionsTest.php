<?php

namespace Tests\Feature\Questions;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /**
     * @test
     */
    public function can_see_answers_when_view_a_published_question()
    {
        $question = factory(Question::class)->state('published')->create();
        create(Answer::class, ['question_id' => $question->id], 40);

        $response = $this->get('/questions/' . $question->id);

        $result = $response->data('answers')->toArray();

        $this->assertCount(20, $result['data']);
        $this->assertEquals(40, $result['total']);
    }
}
