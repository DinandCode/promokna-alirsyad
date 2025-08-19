@extends('templates.navbar')

@section('title', 'Promokna.id')


@section('navbar_content')
    <section id="list-peserta" class="pt-24 px-5 md:px-20">
        <div class="bg-white rounded-2xl shadow-lg p-10">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">List Peserta</h2>
                <p class="text-gray-600">
                    Dibawah ini merupakan list peserta yang sudah terdaftar untuk mengikuti event
                    {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}.
                </p>
            </div>

            <!-- Search Bar -->
            <form class="relative mb-6 flex gap-4">
                <input type="text" name="query" value="{{ request('query') }}" placeholder="Masukkan No.BIB"
                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-purple-600" />
                @if (request('query'))
                    <a href="{{ route('user.peserta') }}"
                        class="bg-slate-500 text-white px-6 py-3 rounded-lg text-lg font-semibold">Bersihkan</a>
                @endif
            </form>

            <!-- Daftar Peserta -->
            <div class="space-y-4">
                @foreach ($participants as $participant)
                    <div class="flex items-center justify-between bg-gray-100 rounded-lg p-4 gap-4">
                        <span
                            class="text-sm bg-green-500 text-white px-3 py-1 rounded-lg">{{ str_pad($participant->bib ?? 0, 4, '0', STR_PAD_LEFT) }}</span>
                        <div class="flex items-center space-x-4 mr-auto">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $participant->bib_name }}</h3>
                                <p class="text-sm text-blue-500">
                                    {{ $participant->full_name }}</p>
                            </div>
                        </div>
                        <a href="{{ route('user.print-bib', $participant->bib) }}"
                            class="bg-red-500 text-white px-3 py-1 rounded-lg" target="_blank" class="btn btn-primary">
                            Unduh BIB
                        </a>
                    </div>
                @endforeach
                @if (count($participants) == 0 && !$query)
                    <p class="text-center">Silahkan lakukan pencarian</p>
                @endif
                @if (count($participants) == 0 && !!$query)
                    <p class="text-center">Tidak ditemukan peserta</p>
                @endif
                {!! gettype($participants) == 'array' ? '' : $participants->links() !!}
            </div>
        </div>
    </section>
@endsection
