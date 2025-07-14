<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo defined('SITE_NAME') ? SITE_NAME : 'Frozen Food Distribution'; ?></title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link href="<?php echo SITE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="<?php echo SITE_URL ?>/index.php">
            <i class="fas fa-snowflake me-2"></i>
            <?php echo defined('SITE_NAME') ? SITE_NAME : 'Frozen Food'; ?>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo SITE_URL ?>/pages/produk.php">Produk</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo SITE_URL ?>/pages/pemesanan.php">Pemesanan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo SITE_URL ?>/pages/admin/login.php">Admin</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <main class="container my-5">
