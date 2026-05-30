@extends('layouts.admin', ['activeTab' => 'monitor'])

@section('title', 'Admin - System Monitor')

@section('content')
<div class="w-full space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Monitor</h1>
        <p class="text-sm text-gray-500 mt-1">Real-time system performance and health.</p>
    </div>

    <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-100 text-center">
        <i class="fa-solid fa-chart-line text-5xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-bold text-gray-600 mb-2">Coming Soon</h3>
        <p class="text-sm text-gray-400 max-w-md mx-auto">System monitoring dashboard will be available in the next update.</p>
    </div>
</div>
@endsection
