<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserAnswer;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class MyAnswers extends Component
{
    use WithPagination;

    // Filters
    public $selectedCategory = 'all';
    public $showHidden = false;

    // For re-answering questions
    public $editingAnswerId = null;
    public $newAnswer = null;
    public $newImportance = null;

    // For confirmation prompts
    public $showDeleteConfirmation = false;
    public $answerIdToDelete = null;

    // To preserve filter state on page reload
    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'showHidden' => ['except' => false],
    ];

    public function mount()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingShowHidden()
    {
        $this->resetPage();
    }

    public function getAnswers()
    {
        $query = UserAnswer::where('user_id', Auth::id())
            ->with(['question.category'])
            ->when(!$this->showHidden, function ($query) {
                return $query->where('hidden', false);
            });

        if ($this->selectedCategory !== 'all') {
            $query->whereHas('question.category', function ($q) {
                $q->where('slug', $this->selectedCategory);
            });
        }

        return $query->latest()->paginate(10);
    }

    public function toggleHideAnswer($answerId)
    {
        $answer = UserAnswer::findOrFail($answerId);

        if ($answer->user_id !== Auth::id()) {
            // Security check
            return;
        }

        $answer->hidden = !$answer->hidden;
        $answer->save();

        $this->dispatch('notify', [
            'message' => $answer->hidden ? 'Answer hidden successfully' : 'Answer unhidden successfully',
            'type' => 'success'
        ]);
    }

    public function confirmDelete($answerId)
    {
        $this->showDeleteConfirmation = true;
        $this->answerIdToDelete = $answerId;
    }

    public function deleteAnswer()
    {
        $answer = UserAnswer::findOrFail($this->answerIdToDelete);

        if ($answer->user_id !== Auth::id()) {
            // Security check
            return;
        }

        $answer->delete();

        $this->showDeleteConfirmation = false;
        $this->answerIdToDelete = null;

        $this->dispatch('notify', [
            'message' => 'Answer removed successfully',
            'type' => 'success'
        ]);
    }

    public function startEdit($answerId)
    {
        $answer = UserAnswer::findOrFail($answerId);

        if ($answer->user_id !== Auth::id()) {
            // Security check
            return;
        }

        $this->editingAnswerId = $answerId;
        $this->newAnswer = $answer->answer;
        $this->newImportance = $answer->importance;
    }

    public function cancelEdit()
    {
        $this->editingAnswerId = null;
        $this->newAnswer = null;
        $this->newImportance = null;
    }

    public function saveEdit()
    {
        $this->validate([
            'newAnswer' => 'required',
            'newImportance' => 'required|in:important,somewhat_important,not_important',
        ]);

        $answer = UserAnswer::findOrFail($this->editingAnswerId);

        if ($answer->user_id !== Auth::id()) {
            // Security check
            return;
        }

        $answer->answer = $this->newAnswer;
        $answer->importance = $this->newImportance;
        $answer->save();

        $this->editingAnswerId = null;
        $this->newAnswer = null;
        $this->newImportance = null;

        $this->dispatch('notify', [
            'message' => 'Answer updated successfully',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.my-answers', [
            'answers' => $this->getAnswers(),
            'categories' => Category::all()
        ]);
    }
}
