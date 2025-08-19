@extends('templates.adminav')
@section('title', 'Daftar Tiket')
@section('navbar_admin')
    <div class="w-full">
        <div class="sm:flex sm:items-center sm:justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-800">Tickets</h1>
            <a href="{{ route('admin.tickets.create') }}"
                class="hidden sm:inline-block px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm text-sm hover:bg-blue-600">
                <i class="fas fa-plus fa-sm text-white-50"></i> Create New Ticket
            </a>

        </div>
        <div class="bg-white shadow rounded-lg mb-4">
            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200" id="dataTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Tiket</th>
                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price</th> --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kuota</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->name }}</td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">{{ number_format($ticket->price, 0, ',', '.') }}
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->quota ?? 'Tak Terbatas' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                                            class="px-3 py-1 bg-yellow-400 text-white rounded-md text-sm hover:bg-yellow-500">Edit</a>
                                        <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600"
                                                onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
