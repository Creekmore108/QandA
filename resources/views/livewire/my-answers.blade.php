<div>
    <!-- Notification component -->
    <div x-data="{ open: false, message: '', type: 'success' }"
         x-on:notify.window="message = $event.detail.message; type = $event.detail.type; open = true; setTimeout(() => { open = false }, 3000)"
         x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed top-4 right-4 z-50 max-w-md">
        <div x-bind:class="{
                'bg-green-100 border-green-400 text-green-700': type === 'success',
                'bg-red-100 border-red-400 text-red-700': type === 'error'
             }"
             class="rounded-md border px-4 py-3 shadow-md"
             role="alert">
            <div class="flex items-center">
                <div x-show="type === 'success'" class="mr-3">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div x-show="type === 'error'" class="mr-3">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span x-text="message"></span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
            <div class="w-full md:w-1/2">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                <select id="category" wire:model.live="selectedCategory" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/2 flex items-end">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showHidden" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Show hidden answers</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Answers List -->
    <div class="space-y-6">
        @forelse($answers as $answer)
            <div class="bg-white rounded-lg shadow overflow-hidden" x-data="{ showOptions: false }">
                <div class="px-4 py-5 sm:px-6 flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                            <span class="mr-2">{{ $answer->question->text }}</span>
                            @if($answer->hidden)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                    Hidden
                                </span>
                            @endif
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Category: <span class="font-medium">{{ $answer->question->category->name }}</span>
                        </p>
                    </div>
                    <div class="mt-2 md:mt-0">
                        <button
                            @click="showOptions = !showOptions"
                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <span x-text="showOptions ? 'Hide Options' : 'Show Options'"></span>
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" :class="{ 'transform rotate-180': showOptions }">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Options and Answer -->
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6" x-show="showOptions" x-transition>
                    <!-- Only show editing form if this answer is being edited -->
                    @if($editingAnswerId === $answer->id)
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Answer</label>

                                @if($answer->question->type === 'yes_no')
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" wire:model="newAnswer" value="Yes" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-3 text-gray-700">Yes</span>
                                        </label>

                                        <label class="flex items-center">
                                            <input type="radio" wire:model="newAnswer" value="No" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-3 text-gray-700">No</span>
                                        </label>
                                    </div>
                                @endif

                                @if($answer->question->type === 'multiple_choice')
                                    <div class="space-y-2">
                                        @foreach($answer->question->options as $option)
                                            <label class="flex items-center">
                                                <input type="radio" wire:model="newAnswer" value="{{ $option }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-3 text-gray-700">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                @error('newAnswer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Importance</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" wire:model="newImportance" value="important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-3 text-gray-700">Important</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" wire:model="newImportance" value="somewhat_important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-3 text-gray-700">Somewhat Important</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" wire:model="newImportance" value="not_important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-3 text-gray-700">Not Important</span>
                                    </label>
                                </div>

                                @error('newImportance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button wire:click="cancelEdit" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>

                                <button wire:click="saveEdit" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Display available options with current answer highlighted -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Options:</h4>
                            <div class="space-y-1">
                                @if($answer->question->type === 'yes_no')
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full {{ $answer->answer === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 line-through' }}">
                                            Yes
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full {{ $answer->answer === 'No' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 line-through' }}">
                                            No
                                        </span>
                                    </div>
                                @endif

                                @if($answer->question->type === 'multiple_choice')
                                    @foreach($answer->question->options as $option)
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full {{ $answer->answer === $option ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 line-through' }}">
                                                {{ $option }}
                                            </span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Your answer -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Your Answer:</h4>
                            <p class="font-medium text-gray-900">{{ $answer->answer }}</p>
                        </div>

                        <!-- Importance rating -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-1">Importance Rating:</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $answer->importance === 'important' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $answer->importance === 'somewhat_important' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $answer->importance === 'not_important' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $answer->importance)) }}
                            </span>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex justify-end space-x-3">
                            <button
                                wire:click="toggleHideAnswer({{ $answer->id }})"
                                type="button"
                                class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                {{ $answer->hidden ? 'Unhide' : 'Hide' }}
                            </button>

                            <button
                                wire:click="startEdit({{ $answer->id }})"
                                type="button"
                                class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Re-answer
                            </button>

                            <button
                                wire:click="confirmDelete({{ $answer->id }})"
                                type="button"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                Delete
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No answers found</h3>
                <p class="text-gray-500 mb-4">
                    @if($selectedCategory !== 'all')
                        You haven't answered any questions in this category yet.
                    @elseif($showHidden)
                        You haven't answered any questions yet.
                    @else
                        You don't have any visible answers. Try enabling "Show hidden answers" or answering some questions.
                    @endif
                </p>
                <a href="{{ route('questions') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Answer Questions
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        <div>
            {{ $answers->links() }}
        </div>
    </div>