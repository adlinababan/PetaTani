<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Tambah Kategori</h3>
      </div>

      <form action="<?= base_url('/kategori/store') ?>" method="post">
        <div class="card-body">
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" required>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="<?= base_url('/kategori') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>