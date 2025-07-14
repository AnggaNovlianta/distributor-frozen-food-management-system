<?php
require_once 'includes/config.php';
require_once 'includes/header.php';
?>

<div class="hero-section bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Distributor Frozen Food Terpercaya</h1>
        <p class="lead">Menyediakan berbagai produk frozen food berkualitas tinggi dengan harga kompetitif</p>
        <a href="pages/produk.php" class="btn btn-light btn-lg mt-3">Lihat Produk Kami</a>
    </div>
</div>

<div class="container">
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                    <h3>Distribusi Cepat</h3>
                    <p>Pengiriman tepat waktu ke seluruh wilayah dengan sistem logistik terintegrasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                    <h3>Kualitas Terjamin</h3>
                    <p>Produk dengan standar kualitas tinggi dan sertifikasi halal</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h3>Layanan 24/7</h3>
                    <p>Customer service siap membantu Anda kapan saja</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Produk Unggulan Kami</h2>
            <div class="row" id="featured-products">
                <!-- Products will be loaded via AJAX -->
                <div class="col-12 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
