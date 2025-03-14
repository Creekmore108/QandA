<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

class QuestionAnswerSystem extends Component
{
    public $questions = [];
    public $currentQuestionIndex = 0;
    public $currentAnswer = null;
    public $importance = null;
    public $showImportanceRating = false;
    public $answeredQuestions = [];
    public $isComplete = false;

    public function mount()
    {
        $this->questions = Question::all()->toArray();

        // Check if there are any questions to answer
        if (count($this->questions) === 0) {
            $this->isComplete = true;
        }

        // Load previously answered questions for this user
        if (Auth::check()) {
            $answeredQuestionIds = UserAnswer::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();

            $this->answeredQuestions = $answeredQuestionIds;

            // Filter out already answered questions
            $this->questions = array_filter($this->questions, function ($question) use ($answeredQuestionIds) {
                return !in_array($question['id'], $answeredQuestionIds);
            });

            // Re-index array
            $this->questions = array_values($this->questions);

            if (count($this->questions) === 0) {
                $this->isComplete = true;
            }
        }
    }

    public function submitAnswer()
    {
        $this->validate([
            'currentAnswer' => 'required',
        ], [
            'currentAnswer.required' => 'Please select an answer.',
        ]);

        $this->showImportanceRating = true;
    }

    public function submitImportance()
    {
        $this->validate([
            'importance' => 'required|in:very_important,somewhat_important,slightly_important,not_important',
        ], [
            'importance.required' => 'Please rate the importance of this question.',
        ]);

        // Store the answer
        if (Auth::check()) {
            UserAnswer::create([
                'user_id' => Auth::id(),
                'question_id' => $this->questions[$this->currentQuestionIndex]['id'],
                'answer' => $this->currentAnswer,
                'importance' => $this->importance,
            ]);

            // Add to answered questions array
            $this->answeredQuestions[] = $this->questions[$this->currentQuestionIndex]['id'];
        }

        // Reset for next question
        $this->currentAnswer = null;
        $this->importance = null;
        $this->showImportanceRating = false;

        // Move to next question
        $this->currentQuestionIndex++;

        // Check if all questions have been answered
        if ($this->currentQuestionIndex >= count($this->questions)) {
            $this->isComplete = true;
        }
    }

    public function skipQuestion()
    {
        // Reset and move to next question
        $this->currentAnswer = null;
        $this->importance = null;
        $this->showImportanceRating = false;
        $this->currentQuestionIndex++;

        // Check if all questions have been answered
        if ($this->currentQuestionIndex >= count($this->questions)) {
            $this->isComplete = true;
        }
    }

    public function resetQuestions()
    {
        if (Auth::check()) {
            // Delete all user answers
            UserAnswer::where('user_id', Auth::id())->delete();
        }

        // Reload questions
        $this->mount();
        $this->currentQuestionIndex = 0;
        $this->isComplete = false;
    }

    public function render()
    {
        return view('livewire.question-answer-system');
    }
}
