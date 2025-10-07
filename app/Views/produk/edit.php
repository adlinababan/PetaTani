<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Produk</h3>
      </div>

      <!-- Tampilkan error validasi jika ada -->
      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ((array) session()->getFlashdata('errors') as $error): ?>
              <li><?= esc($error) ?></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('/produk/update/' . $produk['id']) ?>" method="post" enctype="multipart/form-data">
        <div class="card-body">
          
          <!-- Nama Produk -->
          <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" 
                   value="<?= esc($produk['nama_produk']) ?>" required>
          </div>

          <!-- Deskripsi -->
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"><?= esc($produk['deskripsi']) ?></textarea>
          </div>

          <!-- Harga -->
          <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" 
                   value="<?= esc($produk['harga']) ?>" required>
          </div>

          <!-- Stok -->
          <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" 
                   value="<?= esc($produk['stok']) ?>" required>
          </div>

          <!-- Satuan -->
          <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="satuan" class="form-control" 
                   value="<?= esc($produk['satuan']) ?>">
          </div>

          <!-- Kategori -->
          <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
              <?php foreach ($kategori as $k) : ?>
                <option value="<?= $k['id'] ?>" 
                        <?= $produk['kategori_id'] == $k['id'] ? 'selected' : '' ?>>
                  <?= esc($k['nama_kategori']) ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>

          <!-- Gambar -->
          <div class="form-group">
            <label>Gambar</label><br>
            <?php if (!empty($produk['gambar'])): ?>
              <img src="<?= base_url('uploads/produk/' . $produk['gambar']) ?>" 
                   width="120" class="img-thumbnail mb-2"><br>
            <?php endif; ?>
            <input type="file" name="gambar" class="form-control">
          </div>

          <!-- Link WhatsApp -->
          <div class="form-group">
            <label>Link WhatsApp</label>
            <input type="text" name="link_wa" class="form-control" 
                   value="<?= esc($produk['link_wa']) ?>">
          </div>

          <!-- Tanggal Masuk -->
          <div class="form-group">
            <label for="in_date">Tanggal Masuk</label>
            <input type="date" name="in_date" class="form-control" value="<?= esc(date('Y-m-d', strtotime($produk['in_date']))) ?>" required>
          </div>

          <!-- Tanggal Expired -->
          <div class="form-group">
            <label for="exp_date">Tanggal Expired</label>
           <input type="date" name="exp_date" class="form-control" value="<?= esc(date('Y-m-d', strtotime($produk['exp_date']))) ?>" required>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update</button>
          <a href="<?= base_url('/produk') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
