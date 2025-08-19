@extends('templates.navbar')

@section('title', config('app.name') . ' | Pembayaran Pendaftaran')

@push('head')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush

@section('navbar_content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white mt-14 shadow-lg rounded-2xl p-8 w-full max-w-3xl ">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Pembayaran Pendaftaran</h2>
            <table class="w-full"
                style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
                <tr>
                    <td class="p-5">
                        <h2>Hi, {{ $participant->full_name }}!</h2>
                        <p class="mb-4">Mohon selesaikan pembayaran untuk
                            finalisasi proses pendaftaran.</p>
                        @if ($payment->status === 'paid')
                            <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
                                Pembayaran berhasil!
                            </div>
                            <script>
                                window.addEventListener('DOMContentLoaded', function() {
                                    window.location.href = '{{ route("user.register-success", $participant) }}'
                                });
                            </script>
                        @else
                            <button id="pay-button" type="button"
                                class="px-4 py-2 mb-4 bg-blue-500 text-white rounded w-full block">Bayar
                                Sekarang</button>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3 style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; text-align: center;">
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
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay("{{ $payment->midtrans_snap_token }}", {
                onSuccess: function(result) {
                    console.log(result);
                    window.location.href = "/register-success/{{ $participant->id }}"; // Redirect ke halaman sukses
                },
                onPending: function(result) {
                    console.log(result);
                },
                onError: function(result) {
                    console.log(result);
                }
            });
        };
    </script>
@endpush
