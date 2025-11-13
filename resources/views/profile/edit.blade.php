@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary-900 mb-2">Profile</h1>
        <p class="text-sm text-primary-600">Manage your account information</p>
    </div>

    <!-- E-Library Card -->
    <div class="card p-6 mb-6 text-center">
        <h2 class="text-lg font-bold text-primary-900 mb-4">E-Library Card</h2>
        <div class="bg-primary-50 p-4 rounded-lg mb-4">
            @if(auth()->user()->e_library_card_id)
                <div class="mb-4">
                    {!! auth()->user()->getELibraryCardQrCode() !!}
                </div>
                <p class="text-sm text-primary-600 font-mono">{{ auth()->user()->e_library_card_id }}</p>
            @else
                <p class="text-primary-600">Card ID: Not assigned</p>
            @endif
        </div>
    </div>

    <!-- Update Profile Information -->
    <div class="card p-4 mb-4">
        <h2 class="text-lg font-bold text-primary-900 mb-4">Profile Information</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-primary-900 mb-2">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', auth()->user()->name) }}" 
                    required
                    class="input-underline w-full @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-primary-900 mb-2">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="{{ old('username', auth()->user()->username) }}" 
                    required
                    class="input-underline w-full @error('username') border-red-500 @enderror"
                >
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-primary-900 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', auth()->user()->email) }}" 
                    required
                    class="input-underline w-full @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-primary-900 mb-2">Phone</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone', auth()->user()->phone) }}"
                    class="input-underline w-full @error('phone') border-red-500 @enderror"
                >
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-primary-900 mb-2">Address (Quezon City)</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    value="{{ old('address', auth()->user()->address) }}"
                    placeholder="Enter your address"
                    class="input-underline w-full @error('address') border-red-500 @enderror"
                >
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Barangay -->
            <div>
                <label for="barangay" class="block text-sm font-medium text-primary-900 mb-2">Barangay</label>
                <select 
                    id="barangay" 
                    name="barangay" 
                    class="w-full px-4 py-3 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('barangay') border-red-500 @enderror"
                >
                    <option value="">Select Barangay</option>
                    @php
                        $barangays = \App\Services\AddressValidationService::getBarangays();
                    @endphp
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay }}" {{ old('barangay', auth()->user()->barangay) === $barangay ? 'selected' : '' }}>
                            {{ $barangay }}
                        </option>
                    @endforeach
                </select>
                @error('barangay')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Save Changes
            </button>
        </form>
    </div>

    <!-- Update Password -->
    <div class="card p-4 mb-4">
        <h2 class="text-lg font-bold text-primary-900 mb-4">Update Password</h2>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <label for="current_password" class="block text-sm font-medium text-primary-900 mb-2">Current Password</label>
                <input 
                    type="password" 
                    id="current_password" 
                    name="current_password" 
                    required
                    class="input-underline w-full @error('current_password') border-red-500 @enderror"
                >
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-primary-900 mb-2">New Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="input-underline w-full @error('password') border-red-500 @enderror"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-primary-900 mb-2">Confirm New Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    class="input-underline w-full"
                >
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection
