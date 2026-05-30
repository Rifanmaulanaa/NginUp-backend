@extends('layouts.admin', ['activeTab' => 'properties'])

@section('title', 'Admin - Property Management')

@section('content')
<div class="w-full space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Property Management</h1>
        <p class="text-sm text-gray-500 mt-1">View and manage all properties on the platform.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-3 font-semibold">Property</th>
                    <th class="pb-3 font-semibold">Owner</th>
                    <th class="pb-3 font-semibold">Status</th>
                    <th class="pb-3 font-semibold">Verified</th>
                    <th class="pb-3 font-semibold">Price</th>
                    <th class="pb-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($properti as $p)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($p->nama_properti) }}&background=E9631A&color=fff" class="w-8 h-8 rounded-lg">
                            <span class="font-medium text-gray-800">{{ $p->nama_properti }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-gray-500 text-xs">{{ $p->user->nama ?? 'Unknown' }}</td>
                    <td class="py-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $p->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="py-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $p->verified_status === 'verified' ? 'bg-green-100 text-green-600' : ($p->verified_status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600') }}">
                            {{ $p->verified_status }}
                        </span>
                    </td>
                    <td class="py-3 text-gray-800 font-medium">Rp {{ number_format($p->harga_per_malam, 0, ',', '.') }}</td>
                    <td class="py-3 text-right">
                        <form method="POST" action="/admin/properties/{{ $p->id_properti }}" onsubmit="return confirm('Delete this property?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-[10px] font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-400 text-sm">No properties found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
