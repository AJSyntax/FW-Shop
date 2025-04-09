<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. You can either use your security questions or receive a password reset link via email.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}" id="email-form">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="#" onclick="document.getElementById('security-form').submit(); return false;" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Use Security Questions') }}
                </a>

                <x-button>
                    {{ __('Email Reset Link') }}
                </x-button>
            </div>
        </form>

        <form method="GET" action="{{ route('password.security-questions') }}" id="security-form" class="hidden">
            <input type="hidden" name="email" id="security-email">
        </form>

        <script>
            document.getElementById('email-form').addEventListener('input', function(e) {
                if (e.target.id === 'email') {
                    document.getElementById('security-email').value = e.target.value;
                }
            });
        </script>
    </x-authentication-card>
</x-guest-layout>
