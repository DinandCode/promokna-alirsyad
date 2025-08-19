@extends('templates.adminav')

@section('title', 'Pesan Masuk - Promokna.id')

@section('navbar_admin')

<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-xl font-bold mb-4">Pesan Masuk dari Pengguna</h1>

        {{-- Form pencarian --}}
        <form method="GET" action="{{ route('admin.pesan') }}"
            class="mt-2 mb-4 flex flex-wrap gap-2 md:gap-4 items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan nama atau email"
                value="{{ request('search') }}"
                class="border p-2 rounded-lg w-full sm:w-1/2">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
            @if (!empty(request('search')))
                <a href="{{ route('admin.pesan') }}"
                    class="bg-gray-600 text-white px-4 py-2 rounded">Bersihkan</a>
            @endif
        </form>

        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">No</th>
                        <th class="border p-2">Nama</th>
                        <th class="border p-2">Perusahaan</th>
                        <th class="border p-2">Telepon</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Subjek</th>
                        <th class="border p-2">Pesan</th>
                        <th class="border p-2">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $index => $message)
                        <tr class="bg-white border hover:bg-gray-50">
                            <td class="border p-2 text-center">
                                {{ ($messages->currentPage() - 1) * $messages->perPage() + $index + 1 }}
                            </td>
                            <td class="border p-2">{{ $message->name }}</td>
                            <td class="border p-2">{{ $message->company }}</td>
                            <td class="border p-2">{{ $message->phone }}</td>
                            <td class="border p-2">{{ $message->email }}</td>
                            <td class="border p-2">{{ $message->subject }}</td>
                            <td class="border p-2">{{ $message->message }}</td>
                            <td class="border p-2 text-sm text-gray-600">{{ $message->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Tidak ada pesan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $messages->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</body>

@endsection