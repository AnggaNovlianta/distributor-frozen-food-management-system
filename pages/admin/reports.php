<?php
require_once '../../includes/config.php';
require_once '../../includes/header.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Initialize database
require_once '../../includes/database.php';
$db = new Database();

// Get report parameters
$start_date = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_STRING);
$end_date = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING) ?? 'sales';

// Set default dates if not provided
if (!$start_date) $start_date = date('Y-m-01');
if (!$end_date) $end_date = date('Y-m-d');

// Get report data (in a real app, this would fetch from database)
$salesData = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    'datasets' => [
        [
            'label' => 'Penjualan',
            'data' => [5000000, 7500000, 6000000, 9000000, 8500000, 9500000, 10000000],
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'borderWidth' => 1
        ]
    ]
];

$topProducts = [
    ['name' => 'Nugget Ayam', 'sales' => 150, 'revenue' => 7500000],
    ['name' => 'Sosis Sapi', 'sales' => 120, 'revenue' => 9000000],
    ['name' => 'Bakso Sapi', 'sales' => 100, 'revenue' => 2500000],
    ['name' => 'Tempura Udang', 'sales' => 80, 'revenue' => 4000000],
    ['name' => 'Dumpling Ayam', 'sales' => 60, 'revenue' => 3000000]
];
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Laporan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export PDF</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export Excel</button>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="type" class="form-label">Jenis Laporan</label>
                            <select class="form-select" id="type" name="type">
                                <option value="sales" <?php echo $type === 'sales' ? 'selected' : ''; ?>>Penjualan</option>
                                <option value="products" <?php echo $type === 'products' ? 'selected' : ''; ?>>Produk</option>
                                <option value="customers" <?php echo $type === 'customers' ? 'selected' : ''; ?>>Pelanggan</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($type === 'sales'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Grafik Penjualan</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5><?php echo $type === 'sales' ? 'Rincian Penjualan' : ($type === 'products' ? 'Produk Terlaris' : 'Pelanggan Terbaik'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <?php if ($type === 'sales'): ?>
                                    <th>Tanggal</th>
                                    <th>Jumlah Pesanan</th>
                                    <th>Total Penjualan</th>
                                    <?php elseif ($type === 'products'): ?>
                                    <th>Produk</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Total Pendapatan</th>
                                    <?php else: ?>
                                    <th>Pelanggan</th>
                                    <th>Jumlah Pesanan</th>
                                    <th>Total Belanja</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($type === 'products'): ?>
                                    <?php foreach ($topProducts as $product): ?>
                                    <tr>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['sales']; ?></td>
                                        <td>Rp <?php echo number_format($product['revenue'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Data tidak tersedia</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    if (document.getElementById('salesChart')) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: <?php echo json_encode($salesData); ?>,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php
require_once '../../includes/footer.php';
?>
