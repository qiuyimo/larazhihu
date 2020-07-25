<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {

    }

    public function show($questionId)
    {
        $question = Question::query()->published()->findOrFail($questionId);

        return view('questions.show', [
            'question' => $question,
            'answers' => $question->answers()->paginate(20)
        ]);
    }
}
