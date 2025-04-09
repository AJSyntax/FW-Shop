<x-form-section submit="updateSecurityQuestions">
    <x-slot name="title">
        {{ __('Security Questions') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Set up security questions to help recover your account if you forget your password.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Security Question 1 -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="security_question_1_id" value="{{ __('Security Question 1') }}" />
            <select id="security_question_1_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="state.security_question_1_id">
                <option value="">{{ __('Select a security question') }}</option>
                @foreach($this->securityQuestions as $question)
                    <option value="{{ $question->id }}">{{ $question->question }}</option>
                @endforeach
            </select>
            <x-input-error for="state.security_question_1_id" class="mt-2" />
        </div>

        <!-- Security Answer 1 -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="security_answer_1" value="{{ __('Answer for Question 1') }}" />
            <x-input id="security_answer_1" type="text" class="mt-1 block w-full" wire:model="state.security_answer_1" />
            <x-input-error for="state.security_answer_1" class="mt-2" />
        </div>

        <!-- Security Question 2 -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="security_question_2_id" value="{{ __('Security Question 2') }}" />
            <select id="security_question_2_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="state.security_question_2_id">
                <option value="">{{ __('Select a security question') }}</option>
                @foreach($this->securityQuestions as $question)
                    <option value="{{ $question->id }}">{{ $question->question }}</option>
                @endforeach
            </select>
            <x-input-error for="state.security_question_2_id" class="mt-2" />
        </div>

        <!-- Security Answer 2 -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="security_answer_2" value="{{ __('Answer for Question 2') }}" />
            <x-input id="security_answer_2" type="text" class="mt-1 block w-full" wire:model="state.security_answer_2" />
            <x-input-error for="state.security_answer_2" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
