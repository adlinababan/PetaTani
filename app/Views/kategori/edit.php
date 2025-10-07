<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Kategori</h3>
      </div>

      <form action="<?= base_url('/kategori/update/' . $kategori['id']) ?>" method="post">
        <div class="card-body">
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="<?= esc($kategori['nama_kategori']) ?>" required>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update</button>
          <a href="<?= base_url('/kategori') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>