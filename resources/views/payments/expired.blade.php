<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembayaran Kedaluwarsa</title>
</head>

<body style="background-color: #f9fafb; font-family: Arial, sans-serif; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: white; padding: 24px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <h2 style="color: #dc2626;">Halo {{ $participant->first_name }}!</h2>
        <p>
            Kami ingin memberitahukan bahwa transaksi pembayaran Anda untuk event 
            <strong>{{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}</strong> telah <strong>kedaluwarsa</strong>.
        </p>
        <p>Berikut informasi Anda yang telah kami simpan:</p>
        <ul>
            <li><strong>Nama Lengkap:</strong> {{ $participant->first_name }} {{ $participant->last_name }}</li>
            <li><strong>Email:</strong> {{ $participant->email }}</li>
            <li><strong>No BIB:</strong> {{ $participant->bib ?? '-' }}</li>
        </ul>
        <p>
            Jika Anda masih ingin mengikuti event ini, silakan lakukan pendaftaran ulang melalui website kami.
        </p>

        <p style="margin-top: 24px; color: #6b7280;">
            Terima kasih telah berminat untuk ikut serta. Semoga kita bisa bertemu di kesempatan berikutnya!
        </p>

        <p style="margin-top: 16px; font-weight: bold; color: #111827;">
            Salam,<br>
            Tim {{ config('app.name') }}
        </p>
    </div>
</body>
</html>