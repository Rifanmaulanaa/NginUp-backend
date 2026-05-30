<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    <title>@yield('title', 'Host NginUp')</title>
    @stack('styles')
    <!-- Alpine.js for Sidebar/Interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F8F9FA]" style="font-family: 'Inter', sans-serif;">

<div class="flex min-h-screen w-full relative overflow-hidden">

    {{-- Left Sidebar (Navbar Kiri untuk Desktop) --}}
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col py-6 px-4 shrink-0 hidden md:flex z-40 shadow-sm">
        
        <div class="flex items-center gap-2 mb-10 px-2">
            <div class="w-8 h-8 flex items-center justify-center text-brand-orange">
                <img src="{{ asset('logo_Nginup.png') }}" alt="Logo">
            </div>
            <h1 class="text-2xl font-bold text-brand-orange">
                NginUp
            </h1>
        </div>

        <nav class="flex flex-col gap-2">
            <a href="/host/dashboard" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? 'dashboard') === 'dashboard' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-table-cells-large text-lg w-6 text-center"></i>
                Dashboard
            </a>

            <a href="/host/properties" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'properties' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-house-chimney text-lg w-6 text-center"></i>
                Properties
            </a>

            <a href="/host/add-new" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'add-new' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-circle-plus text-lg w-6 text-center"></i>
                Add New
            </a>

            <a href="/host/reports" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'reports' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-solid fa-money-bill-trend-up text-lg w-6 text-center"></i>
                Reports
            </a>

            <a href="/host/account" class="flex items-center gap-4 p-3 rounded-xl w-full text-left transition-all duration-300 {{ ($activeTab ?? '') === 'account' ? 'text-brand-orange font-semibold bg-brand-orange/10 shadow-sm' : 'text-gray-500 font-semibold hover:bg-gray-50' }}">
                <i class="fa-regular fa-user text-lg w-6 text-center"></i>
                Account
            </a>
        </nav>

        <div class="mt-auto px-2">
            <form method="POST" action="/logout">@csrf
                <button type="submit" class="flex items-center gap-3 text-gray-500 hover:text-red-500 transition-colors px-2 py-2 w-full text-sm"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
            </form>
        </div>

    </aside>

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative w-full custom-scrollbar">

        {{-- Top Navbar (Mobile + Desktop) --}}
        <nav class="flex justify-between items-center px-4 md:px-8 py-4 bg-[#F8F9FA] sticky top-0 z-30">
            {{-- Mobile Left Logo --}}
            <div class="flex items-center gap-2 md:hidden">
                <div class="w-6 h-6 flex items-center justify-center text-brand-orange">
                    <i class="fa-solid fa-face-smile-wink text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-brand-orange">
                    NginUp
                </h1>
            </div>

            {{-- Desktop Left --}}
            <div class="hidden md:block">
                <h2 class="text-xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h2>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-4 text-brand-orange">
                <button class="relative hover:bg-orange-50 p-2 rounded-full transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-1.5 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <button class="md:hidden text-gray-400 hover:text-gray-600 p-2">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="hidden md:flex w-10 h-10 rounded-full bg-orange-100 items-center justify-center text-brand-orange font-bold border-2 border-white shadow-sm ml-2">
                    G
                </div>
            </div>
        </nav>

        {{-- Slot Content --}}
        <div class="px-4 md:px-8 pb-24 md:pb-12 flex-1">
            @yield('content')
        </div>

    </main>

    {{-- Bottom Navigation for Mobile --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 flex justify-between px-2 py-2 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
        <a href="/host/dashboard" class="flex flex-col items-center justify-center w-[18%] gap-1 py-1 rounded-xl transition-all duration-300 {{ ($activeTab ?? 'dashboard') === 'dashboard' ? 'bg-brand-orange text-white' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-table-cells-large text-lg"></i>
            <span class="text-[9px] font-medium">Dashboard</span>
        </a>
        <a href="/host/properties" class="flex flex-col items-center justify-center w-[18%] gap-1 py-1 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'properties' ? 'bg-brand-orange text-white' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-house-chimney text-lg"></i>
            <span class="text-[9px] font-medium">Properties</span>
        </a>
        <a href="/host/add-new" class="flex flex-col items-center justify-center w-[18%] gap-1 py-1 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'add-new' ? 'bg-brand-orange text-white' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-circle-plus text-lg"></i>
            <span class="text-[9px] font-medium">Add New</span>
        </a>
        <a href="/host/reports" class="flex flex-col items-center justify-center w-[18%] gap-1 py-1 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'reports' ? 'bg-brand-orange text-white' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-solid fa-money-bill-trend-up text-lg"></i>
            <span class="text-[9px] font-medium">Reports</span>
        </a>
        <a href="/host/account" class="flex flex-col items-center justify-center w-[18%] gap-1 py-1 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'account' ? 'bg-brand-orange text-white' : 'text-gray-400 hover:text-gray-600' }}">
            <i class="fa-regular fa-user text-lg"></i>
            <span class="text-[9px] font-medium">Account</span>
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
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #E2E8F0;
        border-radius: 10px;
    }
</style>

@stack('scripts')
</body>
</html>
