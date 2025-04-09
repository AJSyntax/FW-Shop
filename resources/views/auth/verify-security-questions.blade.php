<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Please answer your security questions to verify your identity.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.verify-security-questions') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mt-4">
                <x-label for="security_question_1" value="{{ $securityQuestions[1]->question }}" />
                <x-input id="security_answer_1" class="block mt-1 w-full" type="text" name="security_answer_1" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="security_question_2" value="{{ $securityQuestions[2]->question }}" />
                <x-input id="security_answer_2" class="block mt-1 w-full" type="text" name="security_answer_2" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Verify Answers') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
