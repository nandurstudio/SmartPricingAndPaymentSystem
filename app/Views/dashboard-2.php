<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Services</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('services') ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Bookings</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('booking') ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Schedules</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('schedules') ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">Tenant Settings</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?= base_url('tenants') ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">                    <i class="fas fa-chart-area me-1"></i>
                    Booking Analytics
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Service Popularity
                </div>
                <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-0 shadow h-100 py-2">
                <div class="card-body">
                    <h5 class="mb-3">Statistik Platform</h5>
                    <canvas id="statChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Sample chart data - replace with real data
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Bookings",
            lineTension: 0.3,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 50,
            pointBorderWidth: 2,
            data: [10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984],
        }],
    },
    options: {
        scales: {
            xAxes: [{
                gridLines: { display: false },
                ticks: { maxTicksLimit: 7 }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 40000,
                    maxTicksLimit: 5
                },
                gridLines: { color: "rgba(0, 0, 0, .125)", }
            }],
        },
        legend: { display: false }
    }
});

// Bar Chart
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Futsal", "Villa", "Salon", "Kursus", "Restaurant", "Workspace"],
        datasets: [{
            label: "Service Usage",
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: [4215, 5312, 6251, 7841, 9821, 14984],
        }],
    },
    options: {
        scales: {
            xAxes: [{
                gridLines: { display: false },
                ticks: { maxTicksLimit: 6 }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 15000,
                    maxTicksLimit: 5
                },
                gridLines: { display: true }
            }],
        },
        legend: { display: false }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var canvas = document.getElementById('statChart');
    var debugDiv = document.getElementById('canvas-debug');
    if (!canvas) {
        if (debugDiv) debugDiv.innerHTML = 'Canvas statChart TIDAK ditemukan di DOM!';
        console.error('Canvas statChart TIDAK ditemukan di DOM!');
        return;
    } else {
        if (debugDiv) debugDiv.innerHTML = 'Canvas statChart ditemukan. Inisialisasi chart...';
    }
    try {
        var statChart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Tenants', 'Users'],
                datasets: [{
                    label: 'Total',
                    data: [<?= isset($tenantCount) ? $tenantCount : 0 ?>, <?= isset($userCount) ? $userCount : 0 ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Platform Statistics'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });
        if (debugDiv) debugDiv.innerHTML += '<br>Chart berhasil diinisialisasi.';
    } catch (e) {
        if (debugDiv) debugDiv.innerHTML += '<br>Error saat inisialisasi chart: ' + e.message;
        console.error(e);
    }
});
</script>
<?= $this->endSection() ?>