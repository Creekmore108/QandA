<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\Category;

class ManageQuestions extends Component
{
    use WithPagination;

    public $showModal = false;
    public $question = [
        'id' => null,
        'text' => '',
        'type' => 'yes_no',
        'options' => [],
        'category_id' => null
    ];
    public $categories;
    public $newOption = '';
    public $search = '';
    public $confirmingQuestionDeletion = false;
    public $questionIdToDelete = null;
    public $categoryFilter = 'all';

    public function mount()
    {
        $this->categories = Category::all();

        // Set default category if available
        if ($this->categories->count() > 0) {
            $this->question['category_id'] = $this->categories->first()->id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addQuestion()
    {
        $this->resetQuestion();
        $this->showModal = true;
    }

    public function editQuestion($id)
    {
        $question = Question::findOrFail($id);
        $this->question = [
            'id' => $question->id,
            'text' => $question->text,
            'type' => $question->type,
            'options' => $question->options ?? []
        ];
        $this->showModal = true;
    }

    public function saveQuestion()
    {
        $this->validate([
            'question.text' => 'required|min:5',
            'question.type' => 'required|in:yes_no,multiple_choice',
            'question.options' => $this->question['type'] === 'multiple_choice' ? 'required|array|min:2' : '',
            'question.category_id' => 'required|exists:categories,id',
        ]);

        if ($this->question['id']) {
            $existingQuestion = Question::findOrFail($this->question['id']);
            $existingQuestion->update([
                'text' => $this->question['text'],
                'type' => $this->question['type'],
                'options' => $this->question['type'] === 'multiple_choice' ? $this->question['options'] : null,
            ]);
        } else {
            Question::create([
                'text' => $this->question['text'],
                'type' => $this->question['type'],
                'options' => $this->question['type'] === 'multiple_choice' ? $this->question['options'] : null,
            ]);
        }

        $this->showModal = false;
        $this->resetQuestion();
    }

    public function addOption()
    {
        if (!empty($this->newOption)) {
            $this->question['options'][] = $this->newOption;
            $this->newOption = '';
        }
    }

    public function removeOption($index)
    {
        unset($this->question['options'][$index]);
        $this->question['options'] = array_values($this->question['options']);
    }

    public function confirmQuestionDeletion($id)
    {
        $this->confirmingQuestionDeletion = true;
        $this->questionIdToDelete = $id;
    }

    public function deleteQuestion()
    {
        Question::findOrFail($this->questionIdToDelete)->delete();
        $this->confirmingQuestionDeletion = false;
        $this->questionIdToDelete = null;
    }

    private function resetQuestion()
    {
        $this->question = [
            'id' => null,
            'text' => '',
            'type' => 'yes_no',
            'options' => []
        ];
        $this->newOption = '';
    }

    public function render()
    {
        $query = Question::with('category');

        $query = Question::with('category');

        // Filter by category if not "all"
        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }

        // Apply search filter if present
        if (!empty($this->search)) {
            $query->where('text', 'like', '%' . $this->search . '%');
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.manage-questions', [
            'questions' => $questions,
            'categories' => Category::all()
        ]);
        // $questions = Question::where('text', 'like', '%' . $this->search . '%')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        // return view('livewire.admin.manage-questions', [
        //     'questions' => $questions
        // ]);
    }
}
