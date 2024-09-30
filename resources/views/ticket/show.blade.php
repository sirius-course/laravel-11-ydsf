<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-gray-800 dark:text-gray-200">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Tiket
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @session('success')
                <div class="p-4 rounded-md bg-green-50 dark:bg-green-950 border border-green-300 dark:border-green-700 mb-5">
                    <p class="text-green-300 sm:text-sm">
                        {{ session('success') }}
                    </p>
                </div>
            @endsession

            <div class="grid grid-cols-3 gap-4 mb-5">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-5">
                            <label class="block font-medium leading-6 text-gray-900 dark:text-gray-300">Judul:</label>
                            <div class="block font-medium leading-6 text-gray-900 dark:text-gray-300">{{ $ticket->title }}</div>
                        </div>
                        <div class="mb-5">
                            <label class="block font-medium leading-6 text-gray-900 dark:text-gray-300">Deskripsi:</label>
                            <p class="block font-medium leading-6 text-gray-900 dark:text-gray-300">{!! nl2br($ticket->description) !!}</p>
                        </div>
                        @if ($ticket->image)
                            <div class="mb-5">
                                <label class="block font-medium leading-6 text-gray-900 dark:text-gray-300">Gambar:</label>
                                <a class="font-bold bg-cyan-700 hover:bg-cyan-900 p-2 rounded-lg text-xs uppercase text-white" href="{{ Storage::disk('public')->url($ticket->image) }}">Lihat</a>
                            </div>
                        @endif
                        <div>
                            <label class="block font-medium leading-6 text-gray-900 dark:text-gray-300">Pengaju:</label>
                            <div class="block font-medium leading-6 text-gray-900 dark:text-gray-300">{{ $ticket->user->name }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-5"><h2>Riwayat Status Tiket</h2></div>
                        @foreach ($ticket->histories as $status)
                            @php $statusTerakhir = $status->name; @endphp
                            <div class="grid grid-cols-2 mb-1 pb-1 border-b">
                                <div class="uppercase">{{ $status->name }}</div>
                                <div class="text-end">{{ Carbon\Carbon::parse($status->pivot->time_change)->translatedFormat('d M Y, H:i') }}</div>
                            </div>
                        @endforeach

                        <div class="flex justify-center gap-3 mt-5">
                            @if ($statusTerakhir == 'queue')
                                <form action="{{ route('ticket.change-status', ['ticket' => $ticket->id]) }}" method="post" onsubmit="return confirm('Yakin ingin mengubah status ke progressing?')">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $progressing->id }}">
                                    <button class="font-bold bg-cyan-700 hover:bg-cyan-900 p-2 rounded-lg text-xs uppercase text-white">{{ $progressing->name }}</button>
                                </form>
                            @endif

                            @if ($statusTerakhir == 'queue' || $statusTerakhir == 'progressing')
                                <form action="{{ route('ticket.change-status', ['ticket' => $ticket->id]) }}" method="post" onsubmit="return confirm('Yakin ingin mengubah status ke cancel?')">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $cancel->id }}">
                                    <button class="font-bold bg-red-700 hover:bg-red-900 p-2 rounded-lg text-xs uppercase text-white">{{ $cancel->name }}</button>
                                </form>

                                <form action="{{ route('ticket.change-status', ['ticket' => $ticket->id]) }}" method="post" onsubmit="return confirm('Yakin ingin mengubah status ke done?')">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $done->id }}">
                                    <button class="font-bold bg-green-700 hover:bg-green-900 p-2 rounded-lg text-xs uppercase text-white">{{ $done->name }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg col-span-2">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @can('buat komentar')
                        <form action="{{ route('comment.store', ['ticket' => $ticket->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-5">
                                <x-input-label for="input-komentar" value="Komentar" />
                                <x-textarea id="input-komentar" name="komentar" class="mt-1 block w-full" required autofocus autocomplete="komentar" rows="5">{!! nl2br(old('komentar')) !!}</x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('komentar')" />
                            </div>
                            <div class="mb-5">
                                <x-input-label for="input-lampiran" value="Lampiran" />
                                <x-text-input id="input-lampiran" name="lampiran" type="file" class="mt-1 block w-full" accept="*"/>
                                <x-input-error class="mt-2" :messages="$errors->get('lampiran')" />
                            </div>
                            <div class="text-end">
                                <button class="font-bold bg-green-700 hover:bg-green-900 py-2 px-3 rounded-lg uppercase text-white">Kirim Komentar</button>
                            </div>
                        </form>
                    @endcan
                </div>
            </div>
            {{-- sortByDesc adalah fungsi collection laravel: https://laravel.com/docs/11.x/collections#method-sortbydesc --}}
            @foreach ($ticket->comments->sortByDesc('created_at') as $comment)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg col-span-2 mt-5">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between items-center mb-3 font-bold">
                            <div>{{ $comment->user->name }}</div>
                            <div>{{ Carbon\Carbon::parse($comment->created_at)->translatedFormat('d F Y, H:i') }}</div>
                        </div>
                        <div>{!! nl2br($comment->comment) !!}</div>
                        @if (!empty($comment->attachment))
                            <div class="mt-3"><a class="font-bold bg-cyan-700 hover:bg-cyan-900 p-2 rounded-lg text-xs uppercase text-white" href="{{ Storage::disk('public')->url($comment->attachment) }}">Lihat Lampiran</a></div>
                        @endif
                        @can('hapus komentar', $comment)
                            <div class="mt-3">
                                <form action="{{ route('comment.delete', ['comment' => $comment->id]) }}" method="post" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('delete')
                                    <button class="font-bold bg-red-700 hover:bg-red-900 p-2 rounded-lg text-xs uppercase text-white">Hapus Komentar</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
