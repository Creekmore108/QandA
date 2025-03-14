<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Questions Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Total Questions
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $totalQuestions }}
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Total Answers Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Total Answers
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $totalAnswers }}
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Registered Users
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $totalUsers }}
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        Active Respondents
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $activeUsers }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Questions -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Top Questions by Response Count
                </h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($topQuestions as $question)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="truncate">
                                <p class="text-sm font-medium text-indigo-600 truncate">{{ $question->text }}</p>
                                <p class="mt-1 text-sm text-gray-500">ID: {{ $question->id }}</p>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $question->answer_count }} answers
                                </p>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6 text-gray-500 text-sm">
                        No data available
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Importance Distribution -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Question Importance Rating Distribution
                </h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    <!-- Important -->
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-500">Important</div>
                            <div class="text-sm text-gray-700">
                                {{ $importanceDistribution['important']['count'] ?? 0 }}
                                ({{ $importanceDistribution['important']['percentage'] ?? 0 }}%)
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $importanceDistribution['important']['percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Somewhat Important -->
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-500">Somewhat Important</div>
                            <div class="text-sm text-gray-700">
                                {{ $importanceDistribution['somewhat_important']['count'] ?? 0 }}
                                ({{ $importanceDistribution['somewhat_important']['percentage'] ?? 0 }}%)
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $importanceDistribution['somewhat_important']['percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Not Important -->
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-500">Not Important</div>
                            <div class="text-sm text-gray-700">
                                {{ $importanceDistribution['not_important']['count'] ?? 0 }}
                                ({{ $importanceDistribution['not_important']['percentage'] ?? 0 }}%)
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $importanceDistribution['not_important']['percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Activities
            </h3>
        </div>
        <div class="bg-white shadow overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @forelse ($recentActivities as $activity)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-indigo-600 truncate">
                                    {{ $activity->user->name }} answered a question
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <div class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($activity->importance === 'important') bg-green-100 text-green-800
                                        @elseif($activity->importance === 'somewhat_important') bg-yellow-100 text-yellow-800
                                        @elseif($activity->importance === 'not_important') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $activity->importance ?? 'Not Rated')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        <span class="font-semibold">Question:</span> {{ $activity->question->text }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <span class="font-semibold">Answer:</span> {{ $activity->answer }}
                                    </p>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6 text-gray-500 text-sm">
                        No recent activities
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>