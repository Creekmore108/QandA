<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Bob Maholic',
            'email' => 'bobmaholic@yahoo.com',
        ]);

        Question::create([
            'text' => 'Do you regularly exercise?',
            'type' => 'yes_no',
        ]);

        Question::create([
            'text' => 'Do you enjoy outdoor activities?',
            'type' => 'yes_no',
        ]);

        Question::create([
            'text' => 'Are you satisfied with your current work-life balance?',
            'type' => 'yes_no',
        ]);

        // Sample Multiple choice questions
        Question::create([
            'text' => 'How often do you read books?',
            'type' => 'multiple_choice',
            'options' => ['Daily', 'Weekly', 'Monthly', 'Rarely', 'Never'],
        ]);

        Question::create([
            'text' => 'What is your preferred method of transportation?',
            'type' => 'multiple_choice',
            'options' => ['Car', 'Public Transit', 'Bicycle', 'Walking', 'Other'],
        ]);

        Question::create([
            'text' => 'How would you rate your overall stress level?',
            'type' => 'multiple_choice',
            'options' => ['Very Low', 'Low', 'Moderate', 'High', 'Very High'],
        ]);
    }
}
