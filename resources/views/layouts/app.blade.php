<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/app.css')

    <title>@yield('title', 'NginUp')</title>
    @stack('styles')
    <!-- Alpine.js for Sidebar/Interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F5F5F5]">

<div class="flex min-h-screen w-full bg-white relative overflow-hidden shadow-2xl">

    {{-- Left Sidebar (Navbar Kiri) --}}
    <aside class="w-64 bg-white border-r flex flex-col py-6 px-4 shrink-0 hidden md:flex">
        
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 flex items-center justify-center">
                <img src="{{ asset('logo_Nginup.png') }}" alt="NginUp Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-3xl font-bold text-brand-orange-hover">
                NginUp
            </h1>
        </div>

        <nav class="flex flex-col gap-2">
            <a href="/home" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? 'home') === 'home' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-house text-lg w-6"></i>
                Home
            </a>

            <a href="/bookings" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'bookings' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-calendar-check text-lg w-6"></i>
                Bookings
            </a>

            <a href="/rewards" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'rewards' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-gift text-lg w-6"></i>
                Rewards
            </a>

            <a href="/profile" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'profile' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-user text-lg w-6"></i>
                Profile
            </a>
        </nav>

        <div class="mt-auto px-2">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="flex items-center gap-4 text-gray-500 font-semibold p-3 hover:bg-gray-50 rounded-xl transition-colors w-full text-left cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg w-6"></i>
                    Logout
                </button>
            </form>
        </div>

    </aside>

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative">

        {{-- Mobile Top Navbar --}}
        <nav class="md:hidden flex justify-between items-center px-4 py-4 bg-white border-b sticky top-0 z-30 shadow-xs">
            <button class="text-2xl text-brand-orange-hover">
                ☰
            </button>
            <h1 class="text-2xl font-bold text-brand-orange-hover">
                NginUp
            </h1>
            <div class="w-8 h-8 flex items-center justify-center">
                <img src="{{ asset('logo_Nginup.png') }}" alt="NginUp Logo" class="w-full h-full object-contain">
            </div>
        </nav>

        {{-- Slot Content --}}
        @yield('content')

    </main>

    {{-- Bottom Navigation for Mobile --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t flex justify-around py-3 z-50 shadow-lg">
        <a href="/home" class="flex flex-col items-center gap-1 transition-all duration-300 {{ ($activeTab ?? 'home') === 'home' ? 'text-brand-orange font-semibold scale-105' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-house text-xl"></i>
            <span class="text-xs">Home</span>
        </a>
        <a href="/bookings" class="flex flex-col items-center gap-1 transition-all duration-300 {{ ($activeTab ?? '') === 'bookings' ? 'text-brand-orange font-semibold scale-105' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-calendar-check text-xl"></i>
            <span class="text-xs">Bookings</span>
        </a>
        <a href="/rewards" class="flex flex-col items-center gap-1 transition-all duration-300 {{ ($activeTab ?? '') === 'rewards' ? 'text-brand-orange font-semibold scale-105' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-gift text-xl"></i>
            <span class="text-xs">Rewards</span>
        </a>
        <a href="/profile" class="flex flex-col items-center gap-1 transition-all duration-300 {{ ($activeTab ?? '') === 'profile' ? 'text-brand-orange font-semibold scale-105' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-user text-xl"></i>
            <span class="text-xs">Profile</span>
        </a>
    </nav>

</div>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

@stack('scripts')
</body>
</html>
