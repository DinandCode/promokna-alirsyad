<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil</title>
</head>

<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg mt-10">
        <h2 class="text-2xl font-semibold text-gray-800">Hi {{ $participant->bib_name }}!</h2>
        <p class="mt-2 text-gray-600">Anda sudah menjadi peserta dari event
            {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}! Berikut adalah informasi detail Anda:
        </p>
        <hr>

        <table class="w-full mt-4 border border-gray-300">
            <tbody>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Kategori</td>
                    <td class="px-4 py-2">{{ $participant->ticket->name }}</td>
                </tr>
                <tr class="border-b">
                <td class="px-4 py-2 font-medium bg-gray-100">Nama Lengkap</td>
                    <td class="px-4 py-2">{{ $participant->full_name }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">No BIB</td>
                    <td class="px-4 py-2">{{ $participant->bib }}</td>
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
            </tbody>
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

        <p class="mt-4 text-gray-600">Jika ada pertanyaan, silakan hubungi kami.</p>

        <p class="mt-2 text-gray-800 font-semibold">Salam, <br> {{ config('app.name') }}</p>
    </div>
</body>

</html>
