<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'is_active'];

    /**
     * Get users who selected this question as their first security question.
     */
    public function usersWithFirstQuestion()
    {
        return $this->hasMany(User::class, 'security_question_1_id');
    }

    /**
     * Get users who selected this question as their second security question.
     */
    public function usersWithSecondQuestion()
    {
        return $this->hasMany(User::class, 'security_question_2_id');
    }
}
