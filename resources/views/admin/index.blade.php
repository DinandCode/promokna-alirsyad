@extends ('templates.adminav')

@section('title', 'Promokna.id')

@section('navbar_admin')
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6 text-black" x-data="{ peserta: [150, 150, 150, 150, 150, 150] }">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="p-6 border">
                <h2 class="text-lg font-bold mb-4">Pendaftar Bayar vs Gratis</h2>
                <canvas id="pesertaChart" class="bg-white p-6 rounded-lg"></canvas>
            </div>
            <div class="p-6 border">
                <h2 class="text-lg font-bold mb-4">Event Pack </h2>
                <canvas id="eventChart" class="bg-white p-6 rounded-lg"></canvas>
            </div>
        </div>
        <div>
            <div class="p-6 border">
                <h2 class="text-lg font-bold mb-4">Ringkasan Pengambilan </h2>
                <canvas id="ringkasanPengambilan" class="bg-white p-6 rounded-lg"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ringkasanCtx = document.getElementById('ringkasanPengambilan').getContext('2d');

            new Chart(ringkasanCtx, {
                type: 'bar',
                data: {
                    labels: ['Berbayar', 'Gratis'],
                    datasets: [{
                            label: 'Sudah Diambil',
                            data: ['{{ $paidStats['taken'] }}', '{{ $freeStats['taken'] }}'],
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        },
                        {
                            label: 'Belum Diambil',
                            data: ['{{ $paidStats['not_taken'] }}', '{{ $freeStats['not_taken'] }}'],
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Status Pengambilan'
                        },
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('pesertaChart').getContext('2d');
            const eventCtx = document.getElementById('eventChart').getContext('2d');

            const bayar = Number('{{ $paidCount }}');
            const gratis = Number('{{ $freeCount }}');

            const dataDiambil = [Number("{{ $takenStats['taken'] }}"), Number("{{ $takenStats['not_taken'] }}")]

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [`Jersey (${bayar})`, `Non-Jersey (${gratis})`],
                    datasets: [{
                        label: 'Jumlah Peserta',
                        data: [bayar, gratis],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            new Chart(eventCtx, {
                type: 'pie',
                data: {
                    labels: [`Diambil (${dataDiambil[0]})`, `Belum Diambil (${dataDiambil[1]})`],
                    datasets: [{
                        label: 'Pengambilan Event Pack',
                        data: dataDiambil,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endpush
