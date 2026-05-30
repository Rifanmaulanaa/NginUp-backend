@extends('layouts.admin', ['activeTab' => 'messages'])

@section('title', 'Admin - Messages')

@section('content')
<div class="w-full space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Messages</h1>
        <p class="text-sm text-gray-500 mt-1">Platform communication center.</p>
    </div>

    <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-100 text-center">
        <i class="fa-regular fa-message text-5xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-bold text-gray-600 mb-2">Coming Soon</h3>
        <p class="text-sm text-gray-400 max-w-md mx-auto">Message system will be available in the next update.</p>
    </div>
</div>
@endsection
