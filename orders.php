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

// Handle order actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null;

    switch ($action) {
        case 'update_status':
            $status = $_GET['status'] ?? null;
            if ($id && $status) {
                // Update order status (in a real app, this would update database)
                $_SESSION['flash_message'] = 'Status pesanan berhasil diperbarui';
                $_SESSION['flash_type'] = 'success';
                header('Location: orders.php');
                exit;
            }
            break;
    }
}

// Get all orders
$db->query("SELECT o.id, o.customer_name, o.created_at, o.total, o.status 
          FROM orders o 
          ORDER BY o.created_at DESC");
$orders = $db->resultSet();
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manajemen Pesanan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?status=all">Semua</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                            <li><a class="dropdown-item" href="?status=processed">Diproses</a></li>
                            <li><a class="dropdown-item" href="?status=shipped">Dikirim</a></li>
                            <li><a class="dropdown-item" href="?status=completed">Selesai</a></li>
                            <li><a class="dropdown-item" href="?status=cancelled">Dibatalkan</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash_type']; ?> alert-dismissible fade show">
                <?php echo $_SESSION['flash_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php 
                unset($_SESSION['flash_message']);
                unset($_SESSION['flash_type']);
            endif; ?>

            <div class="card">
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
                                <?php foreach ($orders as $order): ?>
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
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="order_detail.php?id=<?php echo $order->id; ?>">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="orders.php?action=update_status&id=<?php echo $order->id; ?>&status=processed">
                                                    <i class="fas fa-cog me-1"></i> Proses
                                                </a></li>
                                                <li><a class="dropdown-item" href="orders.php?action=update_status&id=<?php echo $order->id; ?>&status=shipped">
                                                    <i class="fas fa-truck me-1"></i> Kirim
                                                </a></li>
                                                <li><a class="dropdown-item" href="orders.php?action=update_status&id=<?php echo $order->id; ?>&status=completed">
                                                    <i class="fas fa-check me-1"></i> Selesai
                                                </a></li>
                                                <li><a class="dropdown-item text-danger" href="orders.php?action=update_status&id=<?php echo $order->id; ?>&status=cancelled">
                                                    <i class="fas fa-times me-1"></i> Batalkan
                                                </a></li>
                                            </ul>
                                        </div>
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
