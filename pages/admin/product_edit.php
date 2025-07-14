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

// Get product ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: products.php');
    exit;
}

// Get product data (in a real app, this would fetch from database)
$product = (object)[
    'id' => $id,
    'name' => 'Produk Contoh',
    'description' => 'Deskripsi produk contoh',
    'price' => 100000,
    'stock' => 50,
    'image' => 'default.jpg',
    'status' => 'active'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = 'Nama produk harus diisi';
    if (empty($description)) $errors[] = 'Deskripsi produk harus diisi';
    if (!$price || $price <= 0) $errors[] = 'Harga harus berupa angka positif';
    if (!$stock || $stock < 0) $errors[] = 'Stok harus berupa angka positif';
    if (!in_array($status, ['active', 'inactive'])) $errors[] = 'Status tidak valid';

    if (empty($errors)) {
        // Handle file upload (in a real app)
        $image = $product->image; // Keep existing image unless changed
        
        // Update product (in a real app, this would save to database)
        $_SESSION['flash_message'] = 'Produk berhasil diperbarui';
        $_SESSION['flash_type'] = 'success';
        header('Location: products.php');
        exit;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Produk</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product->name); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($product->description); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="100" value="<?php echo $product->price; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?php echo $product->stock; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                            <?php if ($product->image): ?>
                            <div class="mt-2">
                                <img src="../../assets/images/products/<?php echo $product->image; ?>" alt="Current Image" style="max-width: 200px;">
                                <p class="text-muted small mt-1">Gambar saat ini</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="active" <?php echo $product->status === 'active' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statusActive">Aktif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="inactive" <?php echo $product->status === 'inactive' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statusInactive">Nonaktif</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
