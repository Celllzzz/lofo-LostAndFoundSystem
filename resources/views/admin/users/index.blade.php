<x-app-layout>
    <div class="min-h-screen bg-slate-50 font-sans">
        
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-800">Manajemen Users</h1>
                    <p class="text-xs text-slate-500">Kelola akun Admin, Security, dan Mahasiswa.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition">Kembali</a>
                    
                    <div x-data="{ createOpen: false }">
                        <button @click="createOpen = true" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah User
                        </button>

                        <template x-teleport="body">
                            <div x-show="createOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" 
                                 style="display: none;"
                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                
                                <div class="bg-white rounded-[2rem] w-full max-w-md p-8 shadow-2xl relative transform transition-all" @click.away="createOpen = false"
                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                    
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-xl font-black text-slate-800">Tambah User Baru</h3>
                                        <button @click="createOpen = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                    
                                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                                        @csrf
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                            <input type="text" name="name" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition" placeholder="John Doe">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</label>
                                            <input type="email" name="email" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition" placeholder="email@example.com">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Role</label>
                                            <select name="role" class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition cursor-pointer">
                                                <option value="mahasiswa">Mahasiswa</option>
                                                <option value="security">Security</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Password</label>
                                                <input type="password" name="password" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Konfirmasi</label>
                                                <input type="password" name="password_confirmation" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                            </div>
                                        </div>
                                        <div class="pt-2 flex gap-3">
                                            <button type="button" @click="createOpen = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Batal</button>
                                            <button type="submit" class="flex-1 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-lg transform hover:-translate-y-0.5">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-5">User Info</th>
                                <th class="px-6 py-5 text-center">Role</th>
                                <th class="px-6 py-5">Terdaftar</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-50/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-slate-600 font-bold text-lg shadow-inner">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-sky-600 transition">{{ $user->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border
                                        @if($user->role == 'admin') bg-purple-50 text-purple-700 border-purple-100
                                        @elseif($user->role == 'security') bg-orange-50 text-orange-700 border-orange-100
                                        @else bg-sky-50 text-sky-700 border-sky-100 @endif">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div x-data="{ editOpen: false }">
                                        <div class="flex justify-end gap-2 opacity-80 group-hover:opacity-100 transition">
                                            <button @click="editOpen = true" class="p-2 text-slate-400 hover:text-sky-600 bg-white border border-slate-200 rounded-lg hover:bg-sky-50 transition shadow-sm" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            
                                            @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button onclick="confirmSubmit(event, 'Hapus user {{ $user->name }}?')" class="p-2 text-slate-400 hover:text-rose-600 bg-white border border-slate-200 rounded-lg hover:bg-rose-50 transition shadow-sm" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>

                                        <template x-teleport="body">
                                            <div x-show="editOpen" class="fixed inset-0 z-[99] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" 
                                                 style="display: none;"
                                                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                                
                                                <div class="bg-white rounded-[2rem] w-full max-w-md p-8 shadow-2xl relative text-left transform transition-all" @click.away="editOpen = false"
                                                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-4">
                                                    
                                                    <div class="flex justify-between items-center mb-6">
                                                        <h3 class="text-xl font-black text-slate-800">Edit User</h3>
                                                        <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                                    </div>

                                                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                                                        @csrf @method('PUT')
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama</label>
                                                            <input type="text" name="name" value="{{ $user->name }}" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</label>
                                                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Role</label>
                                                            <select name="role" class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                                                <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                                                <option value="security" {{ $user->role == 'security' ? 'selected' : '' }}>Security</option>
                                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Password Baru (Opsional)</label>
                                                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Konfirmasi Password</label>
                                                            <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 text-sm focus:ring-sky-500 focus:border-sky-500 bg-slate-50 focus:bg-white transition">
                                                        </div>
                                                        <div class="pt-2 flex gap-3">
                                                            <button type="button" @click="editOpen = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">Batal</button>
                                                            <button type="submit" class="flex-1 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-lg">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-slate-50/50 border-t border-slate-100">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
    <style>.animate-fade-in { animation: fadeIn 0.5s ease-out; } @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }</style>
</x-app-layout>