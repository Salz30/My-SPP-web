@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold">Selamat datang, {{ Auth::user()->name }}! 👋</h4>
        <p class="text-muted">Ini adalah ringkasan data sistem keuangan sekolah saat ini.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary text-white rounded p-3 me-3">
                    <i class="fa-solid fa-users fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Siswa</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_siswa }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success text-white rounded p-3 me-3">
                    <i class="fa-solid fa-school fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Kelas</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_kelas }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning text-dark rounded p-3 me-3">
                    <i class="fa-solid fa-file-invoice-dollar fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Total Tagihan</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_tagihan }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center">
                <div class="bg-info text-white rounded p-3 me-3">
                    <i class="fa-solid fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Pembayaran Lunas</h6>
                    <h3 class="mb-0 fw-bold">{{ $pembayaran_lunas }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Grafik Pemasukan Tahun {{ date('Y') }}</h5>
                <canvas id="incomeChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const chartData = @json($chart_data);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Pemasukan (Rp)',
                    data: chartData,
                    backgroundColor: 'rgba(81, 150, 254, 0.2)',
                    borderColor: 'rgba(81, 150, 254, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection