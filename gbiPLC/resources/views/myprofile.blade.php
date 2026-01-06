
<x-nav-bar />
<x-layout>
<div x-data="profileModal()" class="flex flex-col items-center justify-center min-h-screen bg-transparent pt-20">
    <div class="w-full max-w-2xl bg-zinc-900 rounded-2xl shadow-2xl p-8 flex flex-col md:flex-row items-center gap-8 border border-zinc-800">
        <div class="flex flex-col items-center md:items-start">
            <div class="relative group">
                <img src="{{ $user->filename ? asset('storage/' . $user->filename) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="w-36 h-36 rounded-full object-cover border-4 border-blue-700 shadow-lg group-hover:scale-105 transition-transform duration-300 bg-zinc-800" alt="Foto Profil">
                <span class="absolute bottom-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded shadow-md">{{ $user->role ?? 'User' }}</span>
            </div>
            <a href="{{ route('myprofile.edit') }}" class="mt-6 w-40 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-semibold py-2 px-4 rounded-lg shadow transition-all duration-200 flex items-center justify-center">Edit Profil</a>
        </div>
        <div class="flex-1 w-full">
            <h2 class="text-3xl font-bold text-white mb-2">{{ $user->name }}</h2>
            <div class="space-y-2 text-zinc-200 text-base">
                <div><span class="font-semibold text-blue-400">Email:</span> {{ $user->email }}</div>
                <div><span class="font-semibold text-blue-400">Alamat:</span> {{ $user->alamat ?: '-' }}</div>
                <div><span class="font-semibold text-blue-400">Tanggal Lahir:</span> {{ $user->tgl_lahir ?: '-' }}</div>
                <div><span class="font-semibold text-blue-400">No HP:</span> {{ $user->no_HP ?: '-' }}</div>
                <div><span class="font-semibold text-blue-400">Golongan Darah:</span> {{ $user->gol_darah ?: '-' }}</div>
                <div><span class="font-semibold text-blue-400">Instagram:</span> {{ $user->instagram ?: '-' }}</div>
                <div><span class="font-semibold text-blue-400">Facebook:</span> {{ $user->facebook ?: '-' }}</div>
            </div>
        </div>
    </div>
</div>
</x-layout>
