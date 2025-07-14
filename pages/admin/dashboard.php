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
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>
                            Manajemen Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Manajemen Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">
                            <i class="fas fa-chart-bar me-2"></i>
                            Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard Admin</h1>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Produk</h5>
                                    <h2 class="mb-0"><?php 
                                        $db->query("SELECT COUNT(*) as total FROM products");
                                        echo $db->single()->total;
                                    ?></h2>
                                </div>
                                <i class="fas fa-box fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Pesanan Hari Ini</h5>
                                    <h2 class="mb-0"><?php 
                                        $db->query("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
                                        echo $db->single()->total;
                                    ?></h2>
                                </div>
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Pendapatan Bulan Ini</h5>
                                    <h2 class="mb-0">Rp <?php 
                                        $db->query("SELECT SUM(total) as total FROM orders WHERE MONTH(created_at) = MONTH(CURDATE())");
                                        echo number_format($db->single()->total ?? 0, 0, ',', '.');
                                    ?></h2>
                                </div>
                                <i class="fas fa-money-bill-wave fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Pesanan Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db->query("SELECT o.id, o.customer_name, o.created_at, o.total, o.status 
                                          FROM orders o 
                                          ORDER BY o.created_at DESC 
                                          LIMIT 5");
                                $orders = $db->resultSet();
                                
                                foreach ($orders as $order):
                                ?>
                                <tr>
                                    <td>#<?php echo str_pad($order->id, 6, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo $order->customer_name; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($order->created_at)); ?></td>
                                    <td>Rp <?php echo number_format($order->total, 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($order->status) {
                                                case 'pending': echo 'warning'; break;
                                                case 'processed': echo 'info'; break;
                                                case 'shipped': echo 'primary'; break;
                                                case 'completed': echo 'success'; break;
                                                case 'cancelled': echo 'danger'; break;
                                            }
                                        ?>">
                                            <?php echo ucfirst($order->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order_detail.php?id=<?php echo $order->id; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
