@extends('layouts.mobile')

@section('content')
<div class="flex items-center justify-center px-4" style="height: calc(100vh - 3.5rem - 4rem);">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary-900 mb-2">Welcome Back</h1>
            <p class="text-primary-600">Sign in to your Read2Go account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-primary-900 mb-2">Username</label>
                <input 
                    id="username" 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="input-underline w-full @error('username') border-red-500 @enderror"
                    placeholder="Enter your username"
                >
                @error('username')
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
                    autocomplete="current-password"
                    class="input-underline w-full @error('password') border-red-500 @enderror"
                    placeholder="Enter your password"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember" 
                    class="rounded border-primary-300 text-primary-600 focus:ring-primary-500"
                >
                <label for="remember_me" class="ml-2 text-sm text-primary-600">Remember me</label>
            </div>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <div class="text-center">
                    <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        Forgot your password?
                    </a>
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-full">
                Sign In
            </button>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-sm text-primary-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-700">
                        Sign up
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
