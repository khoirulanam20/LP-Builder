<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Daftar Pengguna
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Kelola dan lihat detail aktivitas seluruh pengguna sistem.</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-100">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-[11px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Registered At</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($users as $user)
                                <tr class="bg-white hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400">ID: #{{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 font-mono">{{ $user->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $user->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->is_approved)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">Approved</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-100 uppercase tracking-wider">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <form action="{{ route('superadmin.users.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[11px] font-black {{ $user->is_approved ? 'text-red-500 hover:text-red-700' : 'text-indigo-600 hover:text-indigo-800' }} uppercase tracking-widest transition">
                                                    {{ $user->is_approved ? 'Revoke' : 'Approve' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('superadmin.users.show', $user->id) }}" class="inline-flex items-center bg-gray-900 hover:bg-gray-800 text-white text-[10px] font-bold py-1.5 px-4 rounded-lg shadow-sm transition active:scale-95 uppercase tracking-widest">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 italic">Belum ada pengguna terdaftar.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
