<?php
require_once '../includes/config.php';
require_once '../includes/header.php';

// Initialize database
require_once '../includes/database.php';
$db = new Database();
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Daftar Produk Frozen Food</h2>
        </div>
        <div class="col-md-6 text-end">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Cari</button>
            </form>
        </div>
    </div>

    <div class="row">
        <?php
        // Get products from database
        $db->query("SELECT * FROM products WHERE stock > 0 ORDER BY name ASC");
        $products = $db->resultSet();
        
        if(count($products) > 0):
            foreach($products as $product):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 product-card">
                <img src="../assets/images/products/<?php echo $product->image; ?>" class="card-img-top" alt="<?php echo $product->name; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product->name; ?></h5>
                    <p class="card-text"><?php echo $product->description; ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-primary fw-bold">Rp <?php echo number_format($product->price, 0, ',', '.'); ?></span>
                        <span class="badge bg-success">Stok: <?php echo $product->stock; ?></span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button class="btn btn-primary w-100 add-to-cart" data-id="<?php echo $product->id; ?>">
                        <i class="fas fa-cart-plus me-2"></i>Pesan
                    </button>
                </div>
            </div>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                Tidak ada produk yang tersedia saat ini.
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
