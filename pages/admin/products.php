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

// Handle product actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null;

    switch ($action) {
        case 'delete':
            // Delete product (in a real app, this would delete from database)
            $_SESSION['flash_message'] = 'Produk berhasil dihapus';
            $_SESSION['flash_type'] = 'success';
            header('Location: products.php');
            exit;
            break;
    }
}

// Get all products
$db->query("SELECT * FROM products ORDER BY name ASC");
$products = $db->resultSet();
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manajemen Produk</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="product_add.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Produk
                    </a>
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
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo $product->id; ?></td>
                                    <td>
                                        <img src="../../assets/images/products/<?php echo $product->image; ?>" 
                                             alt="<?php echo $product->name; ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td><?php echo $product->name; ?></td>
                                    <td>Rp <?php echo number_format($product->price, 0, ',', '.'); ?></td>
                                    <td><?php echo $product->stock; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $product->status === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo $product->status === 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="product_edit.php?id=<?php echo $product->id; ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="products.php?action=delete&id=<?php echo $product->id; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
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
