<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pendaftaran</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <table width="600px"
                    style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td style="text-align: center;">
                            <h2>Hi, {{ $participant->bib_name }}!</h2>
                            <p>Terima kasih sudah mendaftarkan dirimu di event
                                {{ \App\Models\Setting::get(\App\Models\Setting::KEY_EVENT_NAME) }}! Mohon selesaikan
                                pembayaran untuk
                                finalisasi proses pendaftaran.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3
                                style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; text-align: center;">
                                Detail Tagihan</h3>
                            <table width="100%" cellspacing="0" cellpadding="5" border="1"
                                style="border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 10px;"><strong>Nama</strong></td>
                                    <td style="padding: 10px;">{{ $participant->full_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px;"><strong>Email</strong></td>
                                    <td style="padding: 10px;">{{ $participant->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px;"><strong>Jumlah Tagihan</strong></td>
                                    <td style="padding: 10px;">
                                        <p><strong>Biaya Pendaftaran</strong></p>
                                        <p>Rp {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                        <p><strong>Biaya Admin</strong></p>
                                        <p>Rp {{ number_format($payment->rate, 2, ',', '.') }}</p>
                                        <p><strong>Total</strong></p>
                                        <p>Rp {{ number_format($payment->total_amount, 2, ',', '.') }}</p>
                                        <p><strong>Total (Dibulatkan)</strong></p>
                                        <p>Rp {{ number_format(ceil($payment->total_amount), 2, ',', '.') }}</p>
                                    </td>
                                </tr>
                            </table>
                            <p style="text-align: center; margin-top: 20px;">
                                <a href="{{ route('payment.pay', ['participant' => $participant, 'payment' => $payment]) }}"
                                    style="background-color: #28a745; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Bayar
                                    Sekarang</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-top: 20px;">
                            <p>Terima kasih! Jika ada pertanyaan, silakan hubungi kami.</p>
                            <p class="mt-2 text-gray-800 font-semibold">Salam, <br> {{ config('app.name') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
