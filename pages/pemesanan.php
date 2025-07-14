<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

// Initialize database
require_once '../includes/database.php';
$db = new Database();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $products = $_POST['products'] ?? [];
    
    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = 'Nama harus diisi';
    if (!$email) $errors[] = 'Email tidak valid';
    if (empty($phone)) $errors[] = 'Nomor telepon harus diisi';
    if (empty($address)) $errors[] = 'Alamat harus diisi';
    if (empty($products)) $errors[] = 'Pilih minimal 1 produk';

    if (empty($errors)) {
        // Process order (in a real app, this would save to database)
        $orderSuccess = true;
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Form Pemesanan</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($orderSuccess) && $orderSuccess): ?>
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Pesanan Berhasil!</h4>
                        <p>Terima kasih telah memesan produk kami. Kami akan segera menghubungi Anda untuk konfirmasi.</p>
                        <a href="../index.php" class="btn btn-primary">Kembali ke Beranda</a>
                    </div>
                    <?php else: ?>
                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Harap isi nama Anda</div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Harap isi email yang valid</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                    <div class="invalid-feedback">Harap isi nomor telepon</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                <div class="invalid-feedback">Harap isi alamat pengiriman</div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h4 class="mb-3">Produk yang Dipesan</h4>
                            <div id="product-selection">
                                <!-- Products will be loaded via JavaScript -->
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <button class="btn btn-primary btn-lg w-100" type="submit">Pesan Sekarang</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
