@extends('templates.navbar')

@section('title', config('app.name') . ' | Pendaftaran Berhasil')

@push('head')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush

@section('navbar_content')
    <div class="relative flex justify-center items-center min-h-screen bg-gray-100 p-8 bg-cover bg-center"
        style="background-image: url('{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_BANNER_URL) }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/80"></div>
        <div class="bg-white mt-14 shadow-lg rounded-2xl p-8 w-full max-w-3xl z-10">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Pendaftaran Berhasil</h2>
            <table class="w-full">
                <tr>
                    <td class="p-5" colspan="2">
                        <h2 class="mb-2">Hi, {{ $participant->bib_name }}!</h2>
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
                            Anda Sudah terdaftar di Event ini!
                        </div>
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Kategori</td>
                    <td class="px-4 py-2">{{ $participant->ticket->name }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Ukuran Jersey</td>
                    <td class="px-4 py-2">{{ $participant->jersey_size }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Nama Lengkap</td>
                    <td class="px-4 py-2">{{ $participant->full_name }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">No BIB</td>
                    <td class="px-4 py-2">{{ str_pad($participant->bib, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Email</td>
                    <td class="px-4 py-2">{{ $participant->email }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Nomor Telepon</td>
                    <td class="px-4 py-2">{{ $participant->phone }}</td>
                </tr>
                @if ($participant->community)
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium bg-gray-100">Komunitas</td>
                        <td class="px-4 py-2">{{ $participant->community }}</td>
                    </tr>
                @endif
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Jenis Kelamin</td>
                    <td class="px-4 py-2">{{ ucfirst($participant->gender) }}</td>
                </tr>
                {{-- <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">NIK</td>
                    <td class="px-4 py-2">{{ $participant->nik }}</td>
                </tr> --}}
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Tempat, Tanggal Lahir</td>
                    <td class="px-4 py-2">{{ $participant->birthplace }}, {{ $participant->birthdate }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Alamat</td>
                    <td class="px-4 py-2">{{ $participant->address }}, {{ $participant->city }}</td>
                </tr>
                {{-- <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Ukuran Jersey</td>
                    <td class="px-4 py-2">{{ $participant->jersey_size }}</td>
                </tr> --}}
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Golongan Darah</td>
                    <td class="px-4 py-2">{{ $participant->blood_type }}</td>
                </tr>
                @if ($participant->medical_history)
                    <tr class="border-b">
                        <td class="px-4 py-2 font-medium bg-gray-100">Riwayat Medis</td>
                        <td class="px-4 py-2">{{ $participant->medical_history }}</td>
                    </tr>
                @endif
                @if ($participant->medical_note)
                    <tr>
                        <td class="px-4 py-2 font-medium bg-gray-100">Catatan Medis</td>
                        <td class="px-4 py-2">{{ $participant->medical_note }}</td>
                    </tr>
                @endif
            </table>

            @php
                $mailNote = \App\Models\Setting::get(\App\Models\Setting::KEY_SUCCESS_EMAIL_NOTE);
            @endphp

            @if ($mailNote != null || !empty($mailNote))
                <p class="mt-2">
                    <strong><u>NOTE:</u></strong>
                <p>{!! $mailNote !!}</p>
                </p>
            @endif
        </div>
    </div>
@endsection
