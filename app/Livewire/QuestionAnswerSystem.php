<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class QuestionAnswerSystem extends Component
{
    public $questions = [];
    public $currentQuestionIndex = 0;
    public $currentAnswer = null;
    public $importance = null;
    public $showImportanceRating = false;
    public $answeredQuestions = [];
    public $isComplete = false;
    public $categories;
    public $selectedCategory = 'all';

    public function mount()
    {
        $this->categories = Category::all();
        $this->selectedCategory = 'all';

        // $this->questions = Question::all()->toArray();
        $this->loadQuestions();

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

    public function filterByCategory($category)
{
    $this->selectedCategory = $category;
    $this->currentQuestionIndex = 0;
    $this->currentAnswer = null;
    $this->importance = null;
    $this->showImportanceRating = false;
    $this->loadQuestions();

    // Check if there are any questions in this category
    if (count($this->questions) === 0) {
        $this->isComplete = true;
    } else {
        $this->isComplete = false;
    }
}

// Replace the existing questions loading with this method
private function loadQuestions()
{
    $query = Question::query();

    // Filter by category if not "all"
    if ($this->selectedCategory !== 'all') {
        $query->whereHas('category', function ($q) {
            $q->where('slug', $this->selectedCategory);
        });
    }

    // Get all questions that match the category filter
    $this->questions = $query->get()->toArray();

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
    }
}

    public function render()
    {
        return view('livewire.question-answer-system');
    }
}
