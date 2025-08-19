<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengambilan Racepack</title>
</head>

<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg mt-10">
        <h2 class="text-2xl font-semibold text-gray-800">Hi {{ $participant->bib_name }}!</h2>

        <p class="mt-2 text-gray-600">
            Terima kasih telah melakukan <strong>pengambilan event/product pack</strong>. Berikut adalah informasi data peserta Anda:
        </p>

        <table class="w-full mt-4 border border-gray-300">
            <tbody>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Event/Produk</td>
                    <td class="px-4 py-2">{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Nama</td>
                    <td class="px-4 py-2">{{ $participant->full_name }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">No BIB</td>
                    <td class="px-4 py-2">60-{{ $participant->bib }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Kategori</td>
                    <td class="px-4 py-2">{{ $participant->payment_status ? 'Berbayar' : 'Gratis' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Ukuran Jersey</td>
                    <td class="px-4 py-2">{{ $participant->jersey_size ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <h3 class="text-lg font-bold mt-6 mb-2">Telah Diambil:</h3>
        <table class="w-full border border-gray-300">
            <tbody>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">Oleh</td>
                    <td class="px-4 py-2">{{ $participant->taken_by }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2 font-medium bg-gray-100">No Telepon</td>
                    <td class="px-4 py-2">{{ $participant->taken_phone }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium bg-gray-100">Hubungan</td>
                    <td class="px-4 py-2">{{ $participant->taken_relationship ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <p class="mt-6 text-gray-600">Terima kasih atas partisipasi Anda. Sampai jumpa di event!</p>

        <p class="mt-4 text-gray-800 font-semibold">Salam, <br> {{ config('app.name') }}</p>
    </div>
</body>

</html>
