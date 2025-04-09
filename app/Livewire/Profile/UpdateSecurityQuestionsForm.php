<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\SecurityQuestion;

class UpdateSecurityQuestionsForm extends Component
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'security_question_1_id' => '',
        'security_answer_1' => '',
        'security_question_2_id' => '',
        'security_answer_2' => '',
    ];

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->state = Auth::user()->only([
            'security_question_1_id',
            'security_answer_1',
            'security_question_2_id',
            'security_answer_2',
        ]);
    }

    /**
     * Update the user's security questions.
     *
     * @return void
     */
    public function updateSecurityQuestions()
    {
        $this->resetErrorBag();

        $this->validate([
            'state.security_question_1_id' => ['required', 'exists:security_questions,id'],
            'state.security_answer_1' => ['required', 'string', 'min:2', 'max:255'],
            'state.security_question_2_id' => ['required', 'exists:security_questions,id', 'different:state.security_question_1_id'],
            'state.security_answer_2' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        Auth::user()->forceFill([
            'security_question_1_id' => $this->state['security_question_1_id'],
            'security_answer_1' => $this->state['security_answer_1'],
            'security_question_2_id' => $this->state['security_question_2_id'],
            'security_answer_2' => $this->state['security_answer_2'],
        ])->save();

        $this->dispatch('saved');
    }

    /**
     * Get the list of security questions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSecurityQuestionsProperty()
    {
        return SecurityQuestion::where('is_active', true)->get();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.update-security-questions-form');
    }
}
