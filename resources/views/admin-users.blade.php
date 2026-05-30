@extends('layouts.admin', ['activeTab' => 'users'])

@section('title', 'Admin - User Management')

@section('content')
<div class="w-full space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all registered users.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-3 font-semibold">User</th>
                    <th class="pb-3 font-semibold">Role</th>
                    <th class="pb-3 font-semibold">Status</th>
                    <th class="pb-3 font-semibold">Joined</th>
                    <th class="pb-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                    <td class="py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=0D8ABC&color=fff" class="w-8 h-8 rounded-full">
                            <div>
                                <span class="font-medium text-gray-800">{{ $user->nama }}</span>
                                <span class="text-xs text-gray-400 block">{{ $user->email }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : ($user->role === 'owner' ? 'bg-cyan-100 text-cyan-600' : 'bg-blue-100 text-blue-600') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="py-3 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="py-3 text-right">
                        <div class="flex gap-2 justify-end">
                            @if ($user->id_user !== Auth::id())
                                <form method="POST" action="/admin/users/{{ $user->id_user }}/status">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $user->status === 'active' ? 'banned' : 'active' }}">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[10px] font-bold {{ $user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} transition-colors">
                                        {{ $user->status === 'active' ? 'Ban' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="/admin/users/{{ $user->id_user }}" onsubmit="return confirm('Delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[10px] font-bold bg-gray-100 text-gray-600 hover:bg-red-100 hover:text-red-600 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">You</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
