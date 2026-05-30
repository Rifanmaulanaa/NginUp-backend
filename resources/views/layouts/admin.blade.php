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
    
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Alpine.js for Sidebar/Interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F4F7FF]" style="font-family: 'Inter', sans-serif;" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden w-full relative">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm transition-opacity md:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"></div>

    <!-- Left Sidebar (Navbar Kiri untuk Desktop & Mobile) -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#FCFCFD] border-r border-gray-100 transform transition-transform duration-300 ease-in-out flex flex-col md:relative md:translate-x-0"
           :class="{'translate-x-0 shadow-2xl': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        
        <!-- Mobile close button -->
        <div class="absolute top-4 right-4 md:hidden">
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- User Profile Area -->
        <div class="p-6 pt-10 flex flex-col items-center border-b border-orange-50/50 relative">
            <div class="w-20 h-20 rounded-full border-[3px] border-orange-100 p-1 mb-3 bg-white shadow-sm overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? 'Admin') }}&background=0D8ABC&color=fff&rounded=true" alt="Admin" class="w-full h-full rounded-full object-cover">
            </div>
            <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->nama ?? 'Admin' }}</h2>
            <p class="text-sm text-gray-500 mb-3">{{ Auth::user()->email ?? '' }}</p>
            <div class="px-3 py-1.5 bg-cyan-100/50 text-cyan-700 text-xs font-semibold rounded-full flex items-center gap-1.5 border border-cyan-100">
                <i class="fa-solid fa-shield-halved text-cyan-600"></i> Global Ecosystem
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto custom-scrollbar px-4 py-6">
            <nav class="flex flex-col gap-1.5">
                <a href="/admin/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? 'dashboard') === 'dashboard' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-solid fa-table-cells-large text-lg w-6 text-center"></i>
                    Dashboard
                </a>

                <a href="/admin/verification" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'verification' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-solid fa-certificate text-lg w-6 text-center"></i>
                    Verification Queue
                </a>

                <a href="/admin/users" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'users' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-solid fa-users text-lg w-6 text-center"></i>
                    User Management
                </a>

                <a href="/admin/payments" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'payments' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-regular fa-credit-card text-lg w-6 text-center"></i>
                    Payments
                </a>

                <a href="/admin/messages" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'messages' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-regular fa-message text-lg w-6 text-center"></i>
                    Messages
                </a>

                <a href="/admin/properties" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'properties' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-regular fa-building text-lg w-6 text-center"></i>
                    Property Management
                </a>

                <a href="/admin/reports" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'reports' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-solid fa-chart-simple text-lg w-6 text-center"></i>
                    Platform Reports
                </a>

                <a href="/admin/monitor" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'monitor' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-solid fa-chart-line text-lg w-6 text-center"></i>
                    System Monitor
                </a>

                <a href="/admin/settings" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 {{ ($activeTab ?? '') === 'settings' ? 'bg-[#9A3B12] text-white shadow-md' : 'text-[#5A4036] hover:bg-orange-50 font-medium' }}">
                    <i class="fa-solid fa-gear text-lg w-6 text-center"></i>
                    Settings
                </a>
            </nav>

            <div class="mt-8 mb-3 px-4">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">SWITCH INTERFACE</span>
            </div>
            
            <nav class="flex flex-col gap-1.5 border-t border-gray-100 pt-3">
                <a href="/host/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 text-gray-500 hover:bg-gray-50 font-medium">
                    <i class="fa-solid fa-house-user text-lg w-6 text-center"></i>
                    Host Portal
                </a>
                <a href="/" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 text-gray-500 hover:bg-gray-50 font-medium">
                    <i class="fa-solid fa-globe text-lg w-6 text-center"></i>
                    User Website
                </a>
            </nav>
        </div>

        <!-- Footer / Logout -->
        <div class="p-4 border-t border-gray-100 bg-[#F9FAFB]/50">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 text-red-500 hover:bg-red-50 font-semibold">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg w-6 text-center"></i>
                    Logout
                </button>
            </form>
            <div class="text-center mt-3 text-xs text-slate-400 font-medium">
                NginUp Admin v2.4.0
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">

        <!-- Top Navbar -->
        <header class="bg-[#F4F7FF] flex justify-between items-center px-4 md:px-8 py-4 sticky top-0 z-30">
            
            <!-- Mobile Menu Trigger & Logo -->
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-1 rounded-lg transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 flex items-center justify-center text-brand-orange">
                        <img src="{{ asset('logo_Nginup.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl font-bold text-brand-orange hidden sm:block">NginUp</span>
                </div>
            </div>

            <!-- Desktop Title -->
            <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2">
                <!-- Can be customized per page -->
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-4">
                <button class="relative text-gray-500 hover:text-brand-orange p-2 rounded-full hover:bg-white transition-colors shadow-sm bg-white">
                    <i class="fa-regular fa-bell text-lg"></i>
                    <span class="absolute top-1.5 right-2 w-2 h-2 bg-brand-orange rounded-full border border-white"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-white shadow-sm border border-gray-100 overflow-hidden cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? 'Admin') }}&background=0D8ABC&color=fff&rounded=true" alt="Admin" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <!-- Page Content Scrollable Area -->
        <div class="flex-1 overflow-y-auto custom-scrollbar px-4 md:px-8 pb-12 pt-2">
            @yield('content')
        </div>

    </main>

</div>

<style>
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
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #CBD5E1;
    }
</style>

</body>
</html>
