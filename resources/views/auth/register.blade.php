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
                    class="w-[100px] h-[100px]"
                />
            </div>

            <h2 class="text-3xl font-bold text-center mb-6 transition-all hover-scale">
                Register for Fandom Wear Shop
            </h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">
                        {{ __('Name') }}
                    </label>
                    <input 
                        id="name"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all hover-scale @error('name') border-red-500 @enderror"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your name"
                    />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                        autocomplete="username"
                        placeholder="Enter your email"
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">
                        {{ __('Password') }}
                    </label>
                    <input 
                        id="password"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all hover-scale @error('password') border-red-500 @enderror"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Enter your password"
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
                        {{ __('Confirm Password') }}
                    </label>
                    <input 
                        id="password_confirmation"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all hover-scale"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    />
                </div>

                <button 
                    type="submit"
                    class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all hover-scale"
                >
                    {{ __('Register') }}
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                {{ __('Already have an account?') }}
                <a class="text-blue-600 hover:underline transition-all hover-scale" href="{{ route('login') }}">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
