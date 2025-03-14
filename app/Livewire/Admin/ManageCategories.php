<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Str;

class ManageCategories extends Component
{
        use WithPagination;

        public $showModal = false;
        public $category = [
            'id' => null,
            'name' => '',
            'description' => ''
        ];
        public $search = '';
        public $confirmingCategoryDeletion = false;
        public $categoryIdToDelete = null;

        protected $rules = [
            'category.name' => 'required|min:3|max:50',
            'category.description' => 'nullable|max:255'
        ];

        public function updatingSearch()
        {
            $this->resetPage();
        }

        public function addCategory()
        {
            $this->resetCategory();
            $this->showModal = true;
        }

        public function editCategory($id)
        {
            $category = Category::findOrFail($id);
            $this->category = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description
            ];
            $this->showModal = true;
        }

        public function saveCategory()
        {
            $this->validate();

            // Generate slug from name
            $slug = Str::slug($this->category['name']);

            // Check if the slug already exists (and it's not the current category)
            $existingCategory = Category::where('slug', $slug)
                ->when($this->category['id'], function ($query) {
                    return $query->where('id', '!=', $this->category['id']);
                })
                ->first();

            if ($existingCategory) {
                // Append a number to make the slug unique
                $count = 1;
                $originalSlug = $slug;
                while (Category::where('slug', $slug)
                    ->when($this->category['id'], function ($query) {
                        return $query->where('id', '!=', $this->category['id']);
                    })
                    ->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
            }

            if ($this->category['id']) {
                $existingCategory = Category::findOrFail($this->category['id']);
                $existingCategory->update([
                    'name' => $this->category['name'],
                    'slug' => $slug,
                    'description' => $this->category['description']
                ]);
            } else {
                Category::create([
                    'name' => $this->category['name'],
                    'slug' => $slug,
                    'description' => $this->category['description']
                ]);
            }

            $this->showModal = false;
            $this->resetCategory();

            $this->dispatch('notify', [
                'message' => $this->category['id'] ? 'Category updated successfully' : 'Category created successfully',
                'type' => 'success'
            ]);
        }

        public function confirmCategoryDeletion($id)
        {
            $this->confirmingCategoryDeletion = true;
            $this->categoryIdToDelete = $id;
        }

        public function deleteCategory()
        {
            $category = Category::findOrFail($this->categoryIdToDelete);

            // Check if the category has questions
            if ($category->questions()->count() > 0) {
                $this->dispatch('notify', [
                    'message' => 'Cannot delete category: It has associated questions. Remove or reassign questions first.',
                    'type' => 'error'
                ]);
            } else {
                $category->delete();
                $this->dispatch('notify', [
                    'message' => 'Category deleted successfully',
                    'type' => 'success'
                ]);
            }

            $this->confirmingCategoryDeletion = false;
            $this->categoryIdToDelete = null;
        }

        private function resetCategory()
        {
            $this->category = [
                'id' => null,
                'name' => '',
                'description' => ''
            ];
        }

        public function render()
        {
            $categories = Category::where('name', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('livewire.admin.manage-categories', [
                'categories' => $categories
            ]);
        }
}
