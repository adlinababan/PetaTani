<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PetaTani - Sistem Informasi Penjualan Digital Hasil Kebun</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="<?= base_url('assets/PetaTani.png') ?>">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
        <img src="<?= base_url('assets/logo_PetaTani.png') ?>" alt="Logo" width="60" height="60" class="me-2">
        <strong>PetaTani</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/produk/detail') ?>">Produk</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/tentang') ?>">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/login') ?>">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
