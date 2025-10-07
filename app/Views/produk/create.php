<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $session = \Config\Services::session(); ?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Tambah Produk</h3>
      </div>
      <form action="<?= base_url('/produk/store') ?>" method="post" enctype="multipart/form-data">
        <div class="card-body">
          <!-- Nama Produk -->
          <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
            <input type="hidden" name="produk_by" value="<?= $session->get('user_id') ?>" required>
          </div>

          <!-- Deskripsi -->
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
          </div>

          <!-- Harga -->
          <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
          </div>

          <!-- Stok -->
          <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
          </div>

          <!-- Satuan -->
          <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" placeholder="Contoh: kg, bungkus, ikat">
          </div>

          <!-- Kategori -->
          <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
              <?php foreach ($kategori as $k) : ?>
                <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <!-- Gambar -->
          <div class="form-group">
            <label>Gambar</label>
            <input type="file" name="gambar" class="form-control">
          </div>

          <!-- Link WhatsApp -->
          <div class="form-group">
            <label>Link WhatsApp</label>
            <input type="text" name="link_wa" class="form-control">
          </div>

          <!-- Tanggal Masuk -->
          <div class="form-group">
            <label for="in_date">Tanggal Masuk</label>
            <input type="date" name="in_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>

          <!-- Tanggal Expired -->
          <div class="form-group">
            <label for="exp_date">Tanggal Expired</label>
            <input type="date" name="exp_date" class="form-control" required>
          </div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="<?= base_url('/produk') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
