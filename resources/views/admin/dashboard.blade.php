@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<section class="content">
    <div class="container-fluid pt-3">

        <!-- ===== CARD ===== -->
        <div class="dashboard-row">

            <!-- Total Transaksi -->
            <div class="small-box bg-info dashboard-card">
                <h4>{{ $totalTransaksi }}</h4>
                <p>Total Transaksi</p>
                <div class="icon"><i class="fas fa-receipt"></i></div>
            </div>

            <!-- Total Pendapatan -->
            <div class="small-box bg-success dashboard-card">
                <h4>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                <p>Total Pendapatan</p>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>

            <!-- Total Produk -->
            <div class="small-box bg-warning dashboard-card">
                <h4>{{ $totalProduk }}</h4>
                <p>Jumlah Produk</p>
                <div class="icon"><i class="fas fa-box"></i></div>
            </div>

            <!-- Total User -->
            <div class="small-box bg-primary dashboard-card">
                <h4>{{ $totalUser }}</h4>
                <p>Total User Terdaftar</p>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>

            <!-- Keuntungan Bulanan -->
            <div class="small-box {{ $persentaseKeuntungan >= 0 ? 'bg-success' : 'bg-danger' }} dashboard-card">
                <h4>{{ $persentaseKeuntungan }}%</h4>
                <p>Keuntungan Bulanan</p>
                <div class="icon">
                    @if($persentaseKeuntungan >= 0)
                        <i class="fas fa-arrow-up"></i>
                    @else
                        <i class="fas fa-arrow-down"></i>
                    @endif
                </div>
            </div>

        </div>

        {{-- ===== GRAFIK ===== --}}
        <div class="row mt-3">

            <!-- BAR CHART -->
            <div class="col-md-8 mb-4">
                <div class="card custom-card h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title text-uppercase small mb-3">Keuntungan Bulanan</h6>
                        <canvas id="barChart" style="height: 260px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- PIE CHART -->
            <div class="col-md-4 mb-4">
                <div class="card custom-card h-100">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <h6 class="card-title text-uppercase small mb-3">Persentase Penjualan Produk</h6>
                        <canvas id="pieChart" style="max-width: 250px; max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ====== BAR CHART ======
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Keuntungan',
                data: @json($grafikBulanan),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // ====== PIE CHART ======
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Produk Laris', 'Produk Tidak Laris'],
            datasets: [{
                data: [{{ $produkLaris }}, {{ $produkTidakLaris }}],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: { responsive: true }
    });
</script>

<style>
    /* GRID CARD */
    .dashboard-row {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .dashboard-card {
        height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        border-radius: 12px;
        position: relative;
        padding: 10px;
        color: white;
    }

    .dashboard-card h4 {
        font-size: 20px;
        margin-bottom: 4px;
        font-weight: bold;
    }

    .dashboard-card p {
        margin-top: 0;
        font-size: 13px;
    }

    .dashboard-card .icon {
        font-size: 30px;
        opacity: 0.25;
        position: absolute;
        right: 10px;
        bottom: 8px;
    }

    /* Grafik Card */
    .custom-card {
        border-radius: 15px;
        padding: 10px;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0px 3px 8px rgba(0,0,0,0.12);
    }

    .custom-card .card-title {
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    /* Responsive */
    @media(max-width: 768px) {
        .dashboard-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection