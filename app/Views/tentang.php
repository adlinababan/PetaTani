<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>

<section class="py-5">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col-md-5 text-center">
         
        <img src="<?= base_url('assets/PetaTani.png') ?>" width="200" height="200" class="img-fluid rounded shadow-sm" alt="Logo PetaTani">
      </div>
       <br>
      <div class="col-md-7">
          <br><br>
        <h2 class="fw-bold text-success">Tentang <span class="text-dark">PetaTani</span></h2>
        <p class="text-muted mt-3">
          <strong>PetaTani</strong> adalah aplikasi pemasaran digital hasil kebun yang dirancang khusus untuk mendukung Usaha Mikro, Kecil, dan Menengah (UMKM) di sektor pertanian. Dengan platform ini, petani dan pelaku usaha lokal dapat menjual hasil kebunnya secara online, memperluas jangkauan pasar, dan meningkatkan pendapatan.
        </p>
        <p class="text-muted">
          Kami percaya bahwa digitalisasi adalah kunci untuk memperkuat ekonomi kerakyatan. Oleh karena itu, PetaTani hadir sebagai solusi yang ramah teknologi dan mudah diakses untuk mempertemukan produk segar dari kebun langsung ke tangan konsumen.
        </p>
      </div>
    </div>

    <div class="row text-center mb-5">
      <div class="col-md-4 mb-4">
        <i class="fas fa-hand-holding-seedling fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">Misi</h5>
        <p class="text-muted">Meningkatkan akses pasar bagi UMKM hasil kebun melalui transformasi digital.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="fas fa-bullseye fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">Visi</h5>
        <p class="text-muted">Menjadi platform utama penjualan hasil kebun lokal secara digital di Indonesia.</p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="fas fa-globe-asia fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">Jangkauan</h5>
        <p class="text-muted">Memberdayakan pelaku tani dari desa hingga kota di seluruh nusantara.</p>
      </div>
    </div>

    <!-- Contact Support Section -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-body">
            <h4 class="fw-bold text-success mb-3">
              <i class="fas fa-headset me-2"></i>Contact Support
            </h4>
            <h3 class="card-title mb-1">TIM : <span class="fw-bold">SYSTECH</span></h3>
            <p class="mb-1"><strong>Institution:</strong> Information Systems BINUS @ Medan</p>
          <h5 class="mb-3">Anggota</h5>
    <div class="table-responsive">
      <table class="table table-sm align-middle mb-0">
        <thead>
          <tr>
            <th style="width: 40%;">Nama</th>
            <th style="width: 25%;">NIM</th>
            <th style="width: 35%;">Program Studi</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>JOCELYN TJONG</strong></td>
            <td>2902712051</td>
            <td>Information Systems</td>
          </tr>
          <tr>
            <td><strong>AUXYLIA LUKE</strong></td>
            <td>2902649953</td>
            <td>Information Systems</td>
          </tr>
          <tr>
            <td><strong>BRYANT MIROSLAVY LIMOEL</strong></td>
            <td>2902647481</td>
            <td>Information Systems</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<?= $this->endSection() ?>
