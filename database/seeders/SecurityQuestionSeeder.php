<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SecurityQuestion;

class SecurityQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            'What was the name of your first pet?',
            'In what city were you born?',
            'What is your mother\'s maiden name?',
            'What high school did you attend?',
            'What was the make of your first car?',
            'What is your favorite movie?',
            'What is the name of your favorite childhood teacher?',
            'What is your favorite color?',
            'What is the name of the street you grew up on?',
            'What was your childhood nickname?'
        ];

        foreach ($questions as $question) {
            SecurityQuestion::updateOrCreate(
                ['question' => $question],
                ['is_active' => true]
            );
        }
    }
}
