@extends('layouts.auth')

@section('title', 'NginUp - Login')

@section('content')

<div x-data="{ showPassword: false, loading: false }" class="w-full">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 bg-brand-orange rounded-2xl flex items-center justify-center shadow-lg shadow-brand-orange/30 mb-4 transform hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('logo_Nginup.png') }}" alt="NginUp Logo" class="w-10 h-10 object-contain brightness-0 invert">
        </div>
        <h1 class="text-3xl font-extrabold text-brand-green tracking-tight">NginUp</h1>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-8 backdrop-blur-sm">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Login to your account</h2>
            <p class="text-sm text-gray-500">Access your dashboard and manage your properties.</p>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-2xl">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Error messages --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form action="/login" method="POST" @submit="loading = true" class="flex flex-col gap-5">
            @csrf

            {{-- Email / Username --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email or Username</label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <input
                        id="email"
                        name="email"
                        type="text"
                        placeholder="Enter your email or username"
                        value="{{ old('email') }}"
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input
                        id="password"
                        name="password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="••••••••"
                        class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                    >
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i :class="showPassword ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash'"></i>
                    </button>
                </div>
            </div>

            {{-- Login Button --}}
            <button
                type="submit"
                :disabled="loading"
                class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-[0.98] text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-brand-orange/30 hover:shadow-xl hover:shadow-brand-orange/40 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed"
            >
                <template x-if="!loading">
                    <span class="flex items-center gap-2">
                        Login
                        <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Logging in...
                    </span>
                </template>
            </button>

        </form>

        {{-- Divider --}}
        <div class="flex items-center gap-4 my-6">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400 font-medium uppercase tracking-wide">Or continue with</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        {{-- Social Login --}}
        <div class="grid grid-cols-2 gap-3">
            <button class="flex items-center justify-center gap-2 py-3 border border-gray-200 rounded-2xl hover:bg-gray-50 hover:border-gray-300 active:scale-[0.98] transition-all duration-300 group">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="text-sm font-semibold text-gray-600 group-hover:text-gray-800 transition-colors">Google</span>
            </button>
            <button class="flex items-center justify-center gap-2 py-3 border border-gray-200 rounded-2xl hover:bg-gray-50 hover:border-gray-300 active:scale-[0.98] transition-all duration-300 group">
                <i class="fa-brands fa-apple text-xl text-gray-800"></i>
                <span class="text-sm font-semibold text-gray-600 group-hover:text-gray-800 transition-colors">Apple</span>
            </button>
        </div>

        {{-- Sign Up Link --}}
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <a href="/register" class="font-bold text-brand-orange hover:text-brand-orange-hover transition-colors">Sign up for free</a>
        </p>

    </div>

    {{-- Security Badge --}}
    <div class="flex items-center justify-center gap-6 mt-6 text-xs text-gray-400">
        <div class="flex items-center gap-1.5">
            <i class="fa-solid fa-shield-halved text-brand-green/50"></i>
            <span>Secure Auth</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="font-bold text-brand-green/40 tracking-tight">PRE<span class="text-brand-orange/50">∞</span>ION</span>
        </div>
        <div class="flex items-center gap-1.5">
            <i class="fa-solid fa-lock text-brand-green/50"></i>
            <span>End-to-End</span>
        </div>
    </div>

</div>

@endsection
