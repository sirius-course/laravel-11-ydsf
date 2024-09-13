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
                @can('hapus tiket', $ticket)
                    <form action="{{ route('ticket.delete', ['ticket' => $ticket->id]) }}" method="post" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @method('delete')
                        @csrf
                        <button class="font-bold bg-red-700 hover:bg-red-900 p-2 rounded-lg text-xs uppercase text-white">Hapus</button>
                    </form>
                @endcan
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ isset($ticket) ? route('ticket.update', ['ticket' => $ticket->id]) : route('ticket.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if (isset($ticket))
                            @method('put')
                        @endif
                        <div class="mb-5">
                            <x-input-label for="input-judul" value="Judul" />
                            <x-text-input id="input-judul" name="judul" type="text" class="mt-1 block w-full" :value="old('judul', $ticket->title ?? '')" required autofocus autocomplete="judul" />
                            <x-input-error class="mt-2" :messages="$errors->get('judul')" />
                        </div>
                        <div class="mb-5">
                            <x-input-label for="input-deskripsi" value="Deskripsi" />
                            <x-textarea id="input-deskripsi" name="deskripsi" class="mt-1 block w-full" required autofocus autocomplete="deskripsi" rows="5">{!! nl2br(old('deskripsi', $ticket->description ?? '')) !!}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('deskripsi')" />
                        </div>
                        <div class="mb-5">
                            <x-input-label for="input-gambar" value="Gambar" />
                            <x-text-input id="input-gambar" name="gambar" type="file" class="mt-1 block w-full" accept="image/*"/>
                            <x-input-error class="mt-2" :messages="$errors->get('gambar')" />
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
