@extends ('templates.adminav')

@section('title', 'Promokna.id')

@section('navbar_admin')

    <body class="bg-gray-100">
        <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-xl font-bold mb-4">Laporan Peserta</h1>

            <form method="GET" action="{{ route('admin.laporan') }}"
                class="mt-6 mb-4 flex flex-wrap gap-2 md:gap-4 items-center">
                <select name="payment_status" class="border rounded-lg px-4 py-2 w-full sm:w-auto">
                    <option value="">-- Status Pendaftaran --</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Berbayar</option>
                    <option value="none" {{ request('payment_status') == 'none' ? 'selected' : '' }}>Gratis</option>
                </select>

                <select name="racepack" class="border rounded-lg px-4 py-2 w-full sm:w-auto">
                    <option value="">-- Status Racepack --</option>
                    <option value="diambil" {{ request('racepack') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                    <option value="belum" {{ request('racepack') == 'belum' ? 'selected' : '' }}>Belum Diambil</option>
                </select>

                <input type="text" name="search" placeholder="Cari peserta..." value="{{ request('search') }}"
                    class="border p-2 rounded-lg w-full sm:w-1/2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
                @if (!empty(request('racepack')) || !empty(request('search')))
                    <a href="{{ route('admin.laporan') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Bersihkan</a>
                @endif
            </form>

            <form method="GET" action="{{ route('admin.laporan.export') }}">
                <input type="hidden" name="racepack" value="{{ request('racepack') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Excel</button>
            </form>

            <div class="mt-4">
                <p>Total: <strong>{{ $participants->total() }}</strong></p>
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">No</th>
                            <th class="border p-2">Nama Lengkap</th>
                            <th class="border p-2">No BIB</th>
                            <th class="border p-2">Nama BIB</th>
                            <th class="border p-2">No Telepon</th>
                            <th class="border p-2">Alamat Email</th>
                            <th class="border p-2">Jenis Kelamin</th>
                            <th class="border p-2">Racepack</th>
                            <th class="border p-2">Jersey</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $index => $participant)
                            <tr class="bg-white border hover:bg-gray-50">
                                <td class="border p-2 text-center">
                                    {{ ($participants->currentPage() - 1) * $participants->perPage() + $index + 1 }}</td>
                                <td class="border p-2">{{ $participant->full_name }}</td>
                                <td class="border p-2">60-{{ $participant->bib }}</td>
                                <td class="border p-2">{{ $participant->bib_name }}</td>
                                <td class="border p-2">{{ $participant->phone }}</td>
                                <td class="border p-2">{{ $participant->email }}</td>
                                <td class="border p-2">{{ $participant->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </td>
                                <td class="border p-2">
                                    {{ $participant->taken_by == null ? 'Belum Diambil' : 'Sudah Diambil' }}
                                </td>
                                <td class="border p-2">
                                    {{ $participant->jersey_size ? 'Dengan Jersey' : 'Tanpa Jersey' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Tidak ada data peserta.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $participants->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </body>

@endsection
