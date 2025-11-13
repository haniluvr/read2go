@extends('layouts.mobile')

@section('content')
<div class="flex items-center justify-center px-4" style="height: calc(100vh - 3.5rem - 4rem);">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary-900 mb-2">Create Account</h1>
            <p class="text-primary-600">Join Read2Go and start borrowing books</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-primary-900 mb-2">Full Name</label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    class="input-underline w-full @error('name') border-red-500 @enderror"
                    placeholder="Enter your full name"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-primary-900 mb-2">Username</label>
                <input 
                    id="username" 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}" 
                    required 
                    autocomplete="username"
                    class="input-underline w-full @error('username') border-red-500 @enderror"
                    placeholder="Choose a username"
                >
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-primary-900 mb-2">Email</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email"
                    class="input-underline w-full @error('email') border-red-500 @enderror"
                    placeholder="Enter your email"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-primary-900 mb-2">Password</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    class="input-underline w-full @error('password') border-red-500 @enderror"
                    placeholder="Create a password"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-primary-900 mb-2">Confirm Password</label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    class="input-underline w-full"
                    placeholder="Confirm your password"
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-full">
                Create Account
            </button>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-primary-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700">
                        Sign in
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
