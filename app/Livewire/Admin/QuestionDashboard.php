<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuestionDashboard extends Component
{
    public function render()
    {
        $totalQuestions = Question::count();
        $totalAnswers = UserAnswer::count();
        $totalUsers = User::count();
        $activeUsers = UserAnswer::distinct('user_id')->count('user_id');

        // Average responses per user
        $avgResponsesPerUser = $totalUsers > 0 ? round($totalAnswers / $totalUsers, 1) : 0;

        // Top questions by response count
        $topQuestions = Question::select('questions.id', 'questions.text', DB::raw('count(user_answers.id) as answer_count'))
            ->leftJoin('user_answers', 'questions.id', '=', 'user_answers.question_id')
            ->groupBy('questions.id', 'questions.text')
            ->orderByDesc('answer_count')
            ->limit(5)
            ->get();

        // Questions by importance
        $importanceDistribution = UserAnswer::select('importance', DB::raw('count(*) as count'))
            ->whereNotNull('importance')
            ->groupBy('importance')
            ->get()
            ->keyBy('importance')
            ->map(function ($item) use ($totalAnswers) {
                return [
                    'count' => $item->count,
                    'percentage' => $totalAnswers > 0 ? round(($item->count / $totalAnswers) * 100, 1) : 0
                ];
            });

        // Recent activities
        $recentActivities = UserAnswer::with(['user', 'question'])
            ->latest()
            ->limit(10)
            ->get();

        return view('livewire.admin.question-dashboard', [
            'totalQuestions' => $totalQuestions,
            'totalAnswers' => $totalAnswers,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'avgResponsesPerUser' => $avgResponsesPerUser,
            'topQuestions' => $topQuestions,
            'importanceDistribution' => $importanceDistribution,
            'recentActivities' => $recentActivities
        ]);
    }
}
