<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-gray-800 dark:text-gray-200">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (isset($ticket))
                    Ubah Tiket
                @else
                    Tambah Tiket
                @endif
            </h2>
            @if (isset($ticket))
                <form action="{{ route('ticket.delete', ['ticket' => $ticket->id]) }}" method="post" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @method('delete')
                    @csrf
                    <button class="font-bold bg-red-700 hover:bg-red-900 p-2 rounded-lg text-xs uppercase text-white">Hapus</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ isset($ticket) ? route('ticket.update', ['ticket' => $ticket->id]) : route('ticket.store') }}" method="post">
                        @csrf
                        @if (isset($ticket))
                            @method('put')
                        @endif
                        <div class="mb-5">
                            <label for="input-judul" class="block font-medium leading-6 text-gray-900 dark:text-gray-300">Judul</label>
                            <div class="mt-2">
                                <input id="input-judul" name="judul" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 focus:ring-2 focus:ring-indigo-600" value="{{ old('judul') ?? $ticket->title ?? '' }}">
                                @error('judul')
                                    <div class="text-red-500 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="input-deskripsi" class="block font-medium leading-6 text-gray-900 dark:text-gray-300">Deskripsi</label>
                            <div class="mt-2">
                                <textarea id="input-deskripsi" name="deskripsi" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 focus:ring-2 focus:ring-indigo-600" rows="5">{{ old('deskripsi') ?? $ticket->description ?? '' }}</textarea>
                                @error('deskripsi')
                                    <div class="text-red-500 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="font-bold bg-green-700 hover:bg-green-900 py-2 px-3 rounded-lg uppercase text-white">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
