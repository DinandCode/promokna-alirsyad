@extends ('templates.adminav')

@section('title', 'Promokna.id')

@section('navbar_admin')
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6 text-black" x-data="{ peserta: [150, 150, 150, 150, 150, 150] }">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 border">
                <h2 class="text-lg font-bold mb-4">Pendaftar Per Kategori</h2>
                <canvas id="pesertaChart" class="bg-white p-6 rounded-lg"></canvas>
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
            const peserta = document.getElementById('pesertaChart').getContext('2d');
            const pengambilan = document.getElementById('ringkasanPengambilan').getContext('2d');

            const ticketChart = @json($ticketChart);
            new Chart(peserta, {
                type: 'pie',
                data: {
                    labels: ticketChart.labels,
                    datasets: [{
                        label: 'Peserta per Tiket',
                        data: ticketChart.data,
                        backgroundColor: [
                            '#064e3b', // dark green
                            '#047857', // medium dark green
                            '#059669', // emerald
                            '#10b981', // green teal
                            '#34d399', // light green
                            '#6ee7b7', // mint green
                            '#a7f3d0', // pale green
                            '#bbf7d0' // very light green
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: '#064e3b' // dark green for readability
                            }
                        }
                    }
                }
            });

            const handledChart = @json($handledChart);
            new Chart(pengambilan, {
                type: 'bar',
                data: {
                    labels: handledChart.labels,
                    datasets: [{
                            label: 'Diambil',
                            data: handledChart.taken,
                            backgroundColor: '#059669', // emerald green
                            borderColor: '#047857', // darker green
                            borderWidth: 1
                        },
                        {
                            label: 'Belum Diambil',
                            data: handledChart.not_taken,
                            backgroundColor: '#bbf7d0', // light mint green
                            borderColor: '#6ee7b7', // soft green border
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                            ticks: {
                                color: '#064e3b' // dark green axis labels
                            }
                        },
                        y: {
                            stacked: true,
                            ticks: {
                                color: '#064e3b'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#064e3b'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
