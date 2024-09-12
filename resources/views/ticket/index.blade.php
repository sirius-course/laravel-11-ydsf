<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-gray-800 dark:text-gray-200">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Daftar Tiket
            </h2>
            <a class="font-bold bg-cyan-700 hover:bg-cyan-900 p-2 rounded-lg text-xs uppercase text-white" href="{{ route('ticket.create') }}">Tambah</a>
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="border-collapse border border-slate-200 dark:border-slate-700 rounded w-full mb-3">
                        <tr>
                            <th class="border-b border-slate-200 dark:border-slate-700 bg-slate-200 dark:bg-slate-700 font-bold p-2">No</th>
                            <th class="border-b border-slate-200 dark:border-slate-700 bg-slate-200 dark:bg-slate-700 font-bold p-2">Judul</th>
                            <th class="border-b border-slate-200 dark:border-slate-700 bg-slate-200 dark:bg-slate-700 font-bold p-2">Pemohon</th>
                            <th class="border-b border-slate-200 dark:border-slate-700 bg-slate-200 dark:bg-slate-700 font-bold p-2">Aksi</th>
                        </tr>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td class="border border-slate-200 dark:border-slate-700 p-2 text-center">{{ $loop->iteration + ($tickets->currentPage() * $tickets->perPage()) - $tickets->perPage()  }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 p-2">{{ $ticket->title }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 p-2">{{ $ticket->user->name }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 p-2 text-center">
                                    <a class="font-bold bg-indigo-700 hover:bg-indigo-900 p-2 rounded-lg text-xs uppercase text-white" href="{{ route('ticket.show', ['ticket' => $ticket->id]) }}">Detail</a>
                                    <a class="font-bold bg-yellow-700 hover:bg-yellow-900 p-2 rounded-lg text-xs uppercase text-white" href="{{ route('ticket.edit', ['ticket' => $ticket->id]) }}">Ubah</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
