<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Bob Maholic',
            'email' => 'bobmaholic@yahoo.com',
            'is_admin' => true,
        ]);



        // Create categories
        $spiritual = Category::create([
            'name' => 'Spiritual',
            'slug' => 'spiritual',
            'description' => 'Questions about spiritual beliefs and practices'
        ]);

        $ethics = Category::create([
            'name' => 'Ethics',
            'slug' => 'ethics',
            'description' => 'Questions about moral and ethical choices'
        ]);

        $sexuality = Category::create([
            'name' => 'Sexuality',
            'slug' => 'sexuality',
            'description' => 'Questions about sexuality and relationships'
        ]);

        $dietExercise = Category::create([
            'name' => 'Diet & Exercise',
            'slug' => 'diet-exercise',
            'description' => 'Questions about nutrition and physical fitness'
        ]);

        $lifestyle = Category::create([
            'name' => 'Lifestyle & Personality',
            'slug' => 'lifestyle-personality',
            'description' => 'Questions about daily habits and personal traits'
        ]);

        $dating = Category::create([
            'name' => 'Dating',
            'slug' => 'dating',
            'description' => 'Questions about dating and relationships'
        ]);

        $fun = Category::create([
            'name' => 'Just for Fun',
            'slug' => 'just-for-fun',
            'description' => 'Light-hearted and fun questions'
        ]);

        // Diet & Exercise questions
        Question::create([
            'text' => 'Do you regularly exercise?',
            'type' => 'yes_no',
            'category_id' => $dietExercise->id
        ]);

        Question::create([
            'text' => 'How often do you exercise each week?',
            'type' => 'multiple_choice',
            'options' => ['Never', '1-2 times', '3-4 times', '5+ times'],
            'category_id' => $dietExercise->id
        ]);

        Question::create([
            'text' => 'Do you follow a specific diet?',
            'type' => 'multiple_choice',
            'options' => ['No specific diet', 'Vegetarian', 'Vegan', 'Keto', 'Paleo', 'Other'],
            'category_id' => $dietExercise->id
        ]);

        // Lifestyle questions
        Question::create([
            'text' => 'Are you satisfied with your current work-life balance?',
            'type' => 'yes_no',
            'category_id' => $lifestyle->id
        ]);

        Question::create([
            'text' => 'How would you describe your personality?',
            'type' => 'multiple_choice',
            'options' => ['Extroverted', 'Introverted', 'Both (Ambivert)', 'It depends on the situation'],
            'category_id' => $lifestyle->id
        ]);

        Question::create([
            'text' => 'What is your preferred method of transportation?',
            'type' => 'multiple_choice',
            'options' => ['Car', 'Public Transit', 'Bicycle', 'Walking', 'Other'],
            'category_id' => $lifestyle->id
        ]);

        // Spiritual questions
        Question::create([
            'text' => 'Do you consider yourself a spiritual person?',
            'type' => 'yes_no',
            'category_id' => $spiritual->id
        ]);

        Question::create([
            'text' => 'How important is spirituality in your life?',
            'type' => 'multiple_choice',
            'options' => ['Very important', 'Somewhat important', 'Not very important', 'Not at all important'],
            'category_id' => $spiritual->id
        ]);

        // Ethics questions
        Question::create([
            'text' => 'Do you believe there are universal moral truths?',
            'type' => 'yes_no',
            'category_id' => $ethics->id
        ]);

        Question::create([
            'text' => 'How do you typically make difficult ethical decisions?',
            'type' => 'multiple_choice',
            'options' => ['Based on consequences', 'Based on principles/rules', 'Based on virtues/character', 'Based on intuition/feeling', 'Based on religious teachings'],
            'category_id' => $ethics->id
        ]);

        // Sexuality questions
        Question::create([
            'text' => 'How important is physical intimacy in a relationship for you?',
            'type' => 'multiple_choice',
            'options' => ['Very important', 'Somewhat important', 'Not very important', 'Not at all important'],
            'category_id' => $sexuality->id
        ]);

        // Dating questions
        Question::create([
            'text' => 'What quality do you value most in a potential partner?',
            'type' => 'multiple_choice',
            'options' => ['Honesty', 'Sense of humor', 'Intelligence', 'Kindness', 'Physical attraction', 'Ambition', 'Shared values'],
            'category_id' => $dating->id
        ]);

        Question::create([
            'text' => 'Do you prefer to date someone with similar or different interests?',
            'type' => 'multiple_choice',
            'options' => ['Very similar interests', 'Mostly similar with some differences', 'Mostly different with some similarities', 'Very different interests'],
            'category_id' => $dating->id
        ]);

        // Fun questions
        Question::create([
            'text' => 'If you could have any superpower, what would it be?',
            'type' => 'multiple_choice',
            'options' => ['Flight', 'Invisibility', 'Super strength', 'Mind reading', 'Teleportation', 'Time travel', 'Healing'],
            'category_id' => $fun->id
        ]);

        Question::create([
            'text' => 'Which season is your favorite?',
            'type' => 'multiple_choice',
            'options' => ['Spring', 'Summer', 'Fall', 'Winter'],
            'category_id' => $fun->id
        ]);
    }
}
