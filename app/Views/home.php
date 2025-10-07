<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="py-5 text-center bg-white">
  <div class="container">
    <img src="<?= base_url('assets/PetaTani.png') ?>" alt="Logo PetaTani" class="mb-3" style="width: 60px;">
    <h1 class="display-5 fw-bold text-success">PetaTani</h1>
    <p class="lead text-muted mb-3">Sistem Informasi Penjualan Digital Hasil Kebun</p>
    <p class="text-muted mb-4">
      Platform penjualan online untuk mendukung petani dan pelaku UMKM menjangkau lebih banyak pelanggan,
      meningkatkan pemasaran, dan memperluas distribusi hasil kebun secara digital.
    </p>
    <a href="<?= base_url('/produk/detail') ?>" class="btn btn-success btn-lg rounded-pill px-4">Lihat Produk</a>
  </div>
</section>

<!-- Highlight Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <i class="fas fa-seedling fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">Produk Segar</h5>
        <p class="text-muted">Langsung dari kebun UMKM lokal, segar dan berkualitas.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="fas fa-store fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">UMKM Lokal</h5>
        <p class="text-muted">Dukung petani dan pengusaha kecil dari daerah Anda.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">Belanja Praktis</h5>
        <p class="text-muted">Pesan online kapan saja, dari mana saja.</p>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
