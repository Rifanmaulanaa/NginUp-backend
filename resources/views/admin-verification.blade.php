@extends('layouts.admin', ['activeTab' => 'verification'])

@section('title', 'Admin - Verification Queue')

@section('content')
<div class="w-full space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Verification Queue</h1>
        <p class="text-sm text-gray-500 mt-1">Approve or reject property listings submitted by hosts.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    {{-- Pending --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
            Pending ({{ $pending->count() }})
        </h3>

        @if ($pending->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No pending properties.</p>
        @else
            <div class="space-y-3">
                @foreach ($pending as $p)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($p->nama_properti) }}&background=E9631A&color=fff" class="w-12 h-12 rounded-xl object-cover">
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">{{ $p->nama_properti }}</h4>
                            <p class="text-xs text-gray-500">by {{ $p->user->nama ?? 'Unknown' }} — {{ $p->kota }}, {{ $p->provinsi }}</p>
                            <span class="text-[10px] text-gray-400">{{ $p->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="/admin/verification/{{ $p->id_properti }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="verified_status" value="verified">
                            <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-xl transition-colors">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" action="/admin/verification/{{ $p->id_properti }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="verified_status" value="rejected">
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-xl transition-colors">
                                <i class="fa-solid fa-xmark"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Verified --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
            Verified ({{ $verified->count() }})
        </h3>

        @if ($verified->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No verified properties.</p>
        @else
            <div class="space-y-2">
                @foreach ($verified as $p)
                <div class="flex items-center justify-between py-2 px-3 hover:bg-gray-50 rounded-xl">
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ $p->nama_properti }}</span>
                        <span class="text-xs text-gray-400 ml-2">by {{ $p->user->nama ?? 'Unknown' }}</span>
                    </div>
                    <span class="text-xs text-green-600 font-medium">{{ $p->created_at->format('d M Y') }}</span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Rejected --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
            Rejected ({{ $rejected->count() }})
        </h3>

        @if ($rejected->isEmpty())
            <p class="text-sm text-gray-400 py-4 text-center">No rejected properties.</p>
        @else
            <div class="space-y-2">
                @foreach ($rejected as $p)
                <div class="flex items-center justify-between py-2 px-3 hover:bg-gray-50 rounded-xl">
                    <div>
                        <span class="text-sm font-medium text-gray-700">{{ $p->nama_properti }}</span>
                        <span class="text-xs text-gray-400 ml-2">by {{ $p->user->nama ?? 'Unknown' }}</span>
                    </div>
                    <span class="text-xs text-red-600 font-medium">{{ $p->created_at->format('d M Y') }}</span>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
