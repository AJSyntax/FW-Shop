<x-guest-layout>
    <div class="bg-gray-100 flex items-center justify-center min-h-screen relative">
        <img 
            src="https://res.cloudinary.com/dmygblav6/image/upload/v1738581630/piclumen-1737719529537_syawkb.png" 
            alt="Background image with a fanbase theme, featuring various fandom symbols and designs" 
            class="absolute inset-0 w-full h-full object-cover z-0"
        />
        
        <div class="bg-white bg-opacity-75 shadow-lg rounded-lg p-8 max-w-md w-full z-10 transition-all hover-scale">
            <div class="flex justify-center mb-6">
                <img 
                    src="https://res.cloudinary.com/dmygblav6/image/upload/v1738584298/piclumen-1738582764073-removebg-preview_uomkiz.png" 
                    alt="Fandom Wear Shop logo, a stylized t-shirt icon with a fanbase theme" 
                    class="w-[100px] h-[100px] transition-all hover-scale-logo"
                />
            </div>

            <h2 class="text-3xl font-bold text-center mb-6">
                Login to Fandom Wear Shop
            </h2>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">
                        {{ __('Email') }}
                    </label>
                    <input 
                        id="email" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all hover-scale @error('email') border-red-500 @enderror" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="Enter your email"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">
                        {{ __('Password') }}
                    </label>
                    <input 
                        id="password" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all hover-scale @error('password') border-red-500 @enderror"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-all hover-scale"
                        />
                        <label for="remember_me" class="ml-2 block text-gray-900">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-blue-600 hover:underline transition-all hover-scale" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all hover-scale-button"
                >
                    {{ __('Login') }}
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                {{ __("Don't have an account?") }}
                <a class="text-blue-600 hover:underline transition-all hover-scale" href="{{ route('register') }}">
                    {{ __('Sign up') }}
                </a>
            </p>
        </div>
    </div>

    <!-- Add the custom styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
        .hover-scale-logo:hover {
            transform: scale(1.1);
        }
        .hover-scale-button:hover {
            transform: scale(1.02);
        }
    </style>

    <!-- Add the required fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</x-guest-layout>
