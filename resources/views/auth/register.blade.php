@extends('layouts.auth')

@section('title', 'NginUp - Daftar Akun')

@section('content')

<div x-data="{
    role: 'traveler',
    showPassword: false,
    loading: false
}" class="w-full">

    {{-- Logo --}}
    <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 bg-brand-orange rounded-2xl flex items-center justify-center shadow-lg shadow-brand-orange/30 mb-3 transform hover:scale-105 transition-transform duration-300">
            <img src="{{ asset('logo_Nginup.png') }}" alt="NginUp Logo" class="w-9 h-9 object-contain brightness-0 invert">
        </div>
        <h1 class="text-2xl font-extrabold text-brand-green tracking-tight">NginUp</h1>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 p-8 backdrop-blur-sm">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500">Bergabunglah sekarang untuk menemukan pengalaman menginap terbaik.</p>
        </div>

        {{-- Error messages --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Role Toggle --}}
        <div class="flex bg-gray-100 rounded-2xl p-1.5 mb-6">
            <button
                type="button"
                @click="role = 'traveler'"
                class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2"
                :class="role === 'traveler'
                    ? 'bg-brand-orange text-white shadow-md shadow-brand-orange/30'
                    : 'text-gray-500 hover:text-gray-700'"
            >
                <i class="fa-solid fa-suitcase-rolling text-xs"></i>
                Traveler
            </button>
            <button
                type="button"
                @click="role = 'owner'"
                class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2"
                :class="role === 'owner'
                    ? 'bg-brand-orange text-white shadow-md shadow-brand-orange/30'
                    : 'text-gray-500 hover:text-gray-700'"
            >
                <i class="fa-solid fa-house-user text-xs"></i>
                Host
            </button>
        </div>

        {{-- Dynamic Role Description --}}
        <div class="mb-5 px-1">
            <div x-show="role === 'traveler'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex items-center gap-2 text-xs text-brand-green/70 bg-brand-green/5 px-3 py-2 rounded-xl">
                <i class="fa-solid fa-compass"></i>
                <span>Jelajahi dan pesan penginapan terbaik di seluruh Indonesia</span>
            </div>
            <div x-show="role === 'owner'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex items-center gap-2 text-xs text-brand-orange/80 bg-brand-orange/5 px-3 py-2 rounded-xl">
                <i class="fa-solid fa-key"></i>
                <span>Kelola propertimu dan mulai dapatkan penghasilan tambahan</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="/register" method="POST" @submit="loading = true" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="role" x-bind:value="role">

            {{-- Nama Lengkap --}}
            <div>
                <label for="nama" class="flex items-center gap-1 text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-circle text-brand-orange text-[5px]"></i>
                    Nama Lengkap
                </label>
                <input
                    id="nama"
                    name="nama"
                    type="text"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama lengkap Anda"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                >
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="flex items-center gap-1 text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-circle text-brand-orange text-[5px]"></i>
                    Username
                </label>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    placeholder="Buat username unik"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                >
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="flex items-center gap-1 text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-circle text-brand-orange text-[5px]"></i>
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                >
            </div>

            {{-- Nomor Telepon --}}
            <div>
                <label for="no_hp" class="flex items-center gap-1 text-sm font-semibold text-gray-700 mb-2">
                    Nomor Telepon (opsional)
                </label>
                <div class="flex gap-2">
                    <div class="flex items-center gap-1.5 px-3 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-600 shrink-0">
                        <img src="https://flagcdn.com/w20/id.png" alt="ID" class="w-5 h-3.5 object-cover rounded-sm">
                        <span class="font-medium">+62</span>
                    </div>
                    <input
                        id="no_hp"
                        name="no_hp"
                        type="tel"
                        value="{{ old('no_hp') }}"
                        placeholder="812 3456 7890"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                    >
                </div>
            </div>

            {{-- Kata Sandi --}}
            <div>
                <label for="password" class="flex items-center gap-1 text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-circle text-brand-orange text-[5px]"></i>
                    Kata Sandi
                </label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
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

            {{-- Konfirmasi Kata Sandi --}}
            <div>
                <label for="password_confirmation" class="flex items-center gap-1 text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-circle text-brand-orange text-[5px]"></i>
                    Konfirmasi Kata Sandi
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    placeholder="Ulangi kata sandi"
                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/30 focus:border-brand-orange transition-all duration-300"
                >
            </div>

            {{-- Terms --}}
            <p class="text-xs text-gray-500 text-center mt-1">
                <i class="fa-solid fa-circle-check text-brand-orange text-[10px] mr-1"></i>
                Saya menyetujui <a href="#" class="font-semibold text-brand-orange hover:text-brand-orange-hover transition-colors underline">Syarat dan Ketentuan</a> serta
                <a href="#" class="font-semibold text-brand-orange hover:text-brand-orange-hover transition-colors underline">Kebijakan Privasi</a> NginUp.
            </p>

            {{-- Register Button --}}
            <button
                type="submit"
                :disabled="loading"
                class="w-full bg-brand-orange hover:bg-brand-orange-hover active:scale-[0.98] text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-brand-orange/30 hover:shadow-xl hover:shadow-brand-orange/40 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed mt-1"
            >
                <template x-if="!loading">
                    <span class="flex items-center gap-2">
                        Daftar Sekarang
                        <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Mendaftarkan...
                    </span>
                </template>
            </button>

        </form>

        {{-- Login Link --}}
        <p class="text-center text-sm text-gray-500 mt-5">
            Sudah punya akun?
            <a href="/login" class="font-bold text-brand-orange hover:text-brand-orange-hover transition-colors">Masuk di sini</a>
        </p>

        {{-- Divider --}}
        <div class="flex items-center gap-4 mt-5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400 font-medium uppercase tracking-wide">Atau daftar dengan</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        {{-- Social Register --}}
        <div class="flex items-center justify-center gap-4 mt-4">
            <button class="w-12 h-12 border border-gray-200 rounded-2xl flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 active:scale-95 transition-all duration-300">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
            </button>
            <button class="w-12 h-12 border border-gray-200 rounded-2xl flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 active:scale-95 transition-all duration-300">
                <i class="fa-brands fa-apple text-xl text-gray-800"></i>
            </button>
        </div>

    </div>

</div>

@endsection
