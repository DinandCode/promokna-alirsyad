<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; color: #333">

    <h2 style="text-align: center; color: #007bff;"><strong>REMINDER!!</strong> <br> {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</h2>

    <p>Salam Gowes Bahagia Untuk Semua,</p>

    <p>
        Selamat kamu telah terdaftar dalam kepesertaan <strong>{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</strong>.
        Berikut kami informasikan pengambilan Ride Pack atas nama:
    </p>

    <!-- Participant Info Table -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tbody>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">No. BIB</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    60-{{ trim($participant->bib) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Nama Lengkap</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    {{ trim($participant->full_name) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Email</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    {{ $participant->email ?? ($participant->email ?? '-') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Nomor HP</td>
                <td style="padding: 8px; border: 1px solid #ccc;">{{ $participant->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Jenis Kelamin</td>
                <td style="padding: 8px; border: 1px solid #ccc;">{{ ucfirst($participant->gender ?? '-') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Tempat / Tanggal Lahir</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    {{ $participant->birthplace ?? '-' }} /
                    {{ \Carbon\Carbon::parse($participant->birthdate ?? '')->format('d-m-Y') }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Alamat</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    {{ $participant->address ?? '-' }}, {{ $participant->city ?? '-' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Kategori</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    {{ $participant->jersey_size ? 'Berbayar' : 'Gratis' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Ukuran Jersey</td>
                <td style="padding: 8px; border: 1px solid #ccc;">{{ $participant->jersey_size ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Dimohon membawa kartu identitas/ktp/sim</strong> saat melakukan pengambilan. Jersey dan No. BIB dapat diambil pada:</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tbody>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Hari/Tanggal</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    Jum'at, 08 Agustus 2025
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Tempat</td>
                <td style="padding: 8px; border: 1px solid #ccc;">
                    Samara Cafe & Eatery, Dukuwaluh (<a href="https://maps.app.goo.gl/VyqEzz4eumzjKMND6">https://maps.app.goo.gl/VyqEzz4eumzjKMND6</a>)
                    <br>
                    200 Meter ke Utara dari Perempatan Dukuwaluh
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ccc;">Waktu</td>
                <td style="padding: 8px; border: 1px solid #ccc;">13:00 - 21:00 WIB</td>
            </tr>
        </tbody>
    </table>

    <p>
        Tunjukkan Email ini saat pengambilan Ride Pack {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}.
        <strong>Catatan:</strong> Tidak Menerima Pengambilan Diluar yang telah ditentukan.
    </p>

    <p style="margin-top: 40px;">
        Terima kasih telah bergabung bersama <strong>{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</strong>!
        Sampai jumpa di garis start!
    </p>

</body>

</html>
