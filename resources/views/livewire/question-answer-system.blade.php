<div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-indigo-600 text-white">
            <h2 class="text-xl font-semibold">Questions & Answers</h2>
        </div>

        @if ($isComplete)
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">All Done!</h3>
                <p class="text-gray-600 mb-4">You've answered all the questions.</p>
                <button
                    wire:click="resetQuestions"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Start Over
                </button>
            </div>
        @else
            <div class="p-6">
                <!-- Progress bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}</span>
                        <span>{{ round(($currentQuestionIndex / count($questions)) * 100) }}% complete</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($currentQuestionIndex / count($questions)) * 100 }}%"></div>
                    </div>
                </div>

                @if (!$showImportanceRating)
                    <!-- Question display -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $questions[$currentQuestionIndex]['text'] }}</h3>

                        <!-- Yes/No question -->
                        @if ($questions[$currentQuestionIndex]['type'] === 'yes_no')
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="yes">
                                    <input id="yes" type="radio" wire:model="currentAnswer" value="Yes" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-gray-700">Yes</span>
                                </label>

                                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="no">
                                    <input id="no" type="radio" wire:model="currentAnswer" value="No" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-gray-700">No</span>
                                </label>
                            </div>
                        @endif

                        <!-- Multiple choice question -->
                        @if ($questions[$currentQuestionIndex]['type'] === 'multiple_choice')
                            <div class="space-y-2">
                                @foreach ($questions[$currentQuestionIndex]['options'] as $option)
                                    <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="{{ $option }}">
                                        <input id="{{ $option }}" type="radio" wire:model="currentAnswer" value="{{ $option }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-3 text-gray-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @error('currentAnswer')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-6 flex justify-between">
                            <button
                                wire:click="skipQuestion"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Skip
                            </button>

                            <button
                                wire:click="submitAnswer"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Importance rating -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">How important is this question to you?</h3>
                        <p class="text-gray-600 mb-4">{{ $questions[$currentQuestionIndex]['text'] }}</p>

                        <div class="space-y-2">
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="very">
                                <input id="very" type="radio" wire:model="importance" value="very_important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-3 text-gray-700">Very Important</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="somewhat">
                                <input id="somewhat" type="radio" wire:model="importance" value="somewhat_important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-3 text-gray-700">Somewhat Important</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="slightly">
                                <input id="slighly" type="radio" wire:model="importance" value="slightly_important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-3 text-gray-700">Slightly Important</span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" for="not">
                                <input id="not" type="radio" wire:model="importance" value="not_important" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-3 text-gray-700">Not Important</span>
                            </label>
                        </div>

                        @error('importance')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-6 flex justify-end">
                            <button
                                wire:click="submitImportance"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Continue
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>