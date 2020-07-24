<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    /**
     * @test
     */
    public function testUserCanViewQuestions()
    {
        // 0. 抛出异常.
        $this->withoutExceptionHandling();

        // 1. 假设 /questions 路由存在

        // 2. 访问链接 /questions
        $testRes = $this->get('/questions');

        // 3. 正常返回 200
        $testRes->assertStatus(200);
    }
}
