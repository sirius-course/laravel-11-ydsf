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
            <div class="grid grid-cols-3 gap-4">
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
        </div>
    </div>
</x-app-layout>
