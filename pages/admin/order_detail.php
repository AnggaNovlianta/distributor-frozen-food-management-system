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

// Get order ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: orders.php');
    exit;
}

// Get order data (in a real app, this would fetch from database)
$order = (object)[
    'id' => $id,
    'order_number' => 'ORD-' . str_pad($id, 6, '0', STR_PAD_LEFT),
    'customer_name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '081234567890',
    'address' => 'Jl. Contoh No. 123, Jakarta',
    'created_at' => date('Y-m-d H:i:s'),
    'status' => 'processed',
    'total' => 350000,
    'items' => [
        (object)[
            'product_id' => 1,
            'name' => 'Nugget Ayam',
            'price' => 50000,
            'quantity' => 2,
            'subtotal' => 100000
        ],
        (object)[
            'product_id' => 2,
            'name' => 'Sosis Sapi',
            'price' => 75000,
            'quantity' => 3,
            'subtotal' => 225000
        ],
        (object)[
            'product_id' => 3,
            'name' => 'Bakso Sapi',
            'price' => 25000,
            'quantity' => 1,
            'subtotal' => 25000
        ]
    ]
];
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Detail Pesanan #<?php echo $order->order_number; ?></h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="orders.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td><?php echo $order->customer_name; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $order->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td><?php echo $order->phone; ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?php echo $order->address; ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pesan</th>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Status Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item <?php echo $order->status === 'pending' ? 'active' : 'completed'; ?>">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>Pesanan Dibuat</h6>
                                        <p class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
                                    </div>
                                </div>
                                <div class="timeline-item <?php echo in_array($order->status, ['processed', 'shipped', 'completed']) ? 'completed' : ($order->status === 'pending' ? '' : 'cancelled'); ?>">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>Pesanan Diproses</h6>
                                        <?php if (in_array($order->status, ['processed', 'shipped', 'completed'])): ?>
                                        <p class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($order->created_at) + 3600); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="timeline-item <?php echo in_array($order->status, ['shipped', 'completed']) ? 'completed' : ($order->status === 'cancelled' ? 'cancelled' : ''); ?>">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>Pesanan Dikirim</h6>
                                        <?php if (in_array($order->status, ['shipped', 'completed'])): ?>
                                        <p class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($order->created_at) + 7200); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="timeline-item <?php echo $order->status === 'completed' ? 'completed' : ($order->status === 'cancelled' ? 'cancelled' : ''); ?>">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6><?php echo $order->status === 'completed' ? 'Pesanan Selesai' : 'Pesanan Dibatalkan'; ?></h6>
                                        <?php if (in_array($order->status, ['completed', 'cancelled'])): ?>
                                        <p class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($order->created_at) + 10800); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order->items as $item): ?>
                                <tr>
                                    <td><?php echo $item->name; ?></td>
                                    <td>Rp <?php echo number_format($item->price, 0, ',', '.'); ?></td>
                                    <td><?php echo $item->quantity; ?></td>
                                    <td>Rp <?php echo number_format($item->subtotal, 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>Rp <?php echo number_format($order->total, 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
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
