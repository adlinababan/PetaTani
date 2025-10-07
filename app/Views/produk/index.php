<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $session = \Config\Services::session(); ?>

<section class="content">
  <div class="container-fluid">

    <!-- Flash Success Message -->
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <div class="card">
      <!-- Header: Seller Role (SLR) -->
      <?php if ($session->get('group_code') == 'SLR') : ?>
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Daftar Produk</h3>
          <a href="<?= base_url('/produk/create') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus-circle"></i> Tambah Produk
          </a>
        </div>
      <?php endif; ?>

      <!-- Header: Admin Role (ADM) -->
      <?php if ($session->get('group_code') == 'ADM') : ?>
        <div class="card-header">
          <form method="post">
            <div class="row">
              <div class="col-md-6">
                <label>Nama Penjual</label>
                <select id="srcseller" name="srcseller" class="form-control">
                  <option value="">All</option>
                  <?php foreach ($list_seller as $rec) : ?>
                    <option value="<?= $rec->id ?>"><?= $rec->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary w-100 mt-2">
                  Cari
                </button>
              </div>
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- Table Body -->
      <div class="card-body">
        <table id="productTable" class="table table-bordered table-hover text-nowrap text-center mb-0">
         <thead>
  <tr>
    <th>No</th>
    <th>Penjual</th>
    <th>Nama</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Tgl Masuk</th>
    <th>Exp</th>
    <th>Gambar</th>
    <th>Aksi</th>
  </tr>
</thead>

<!-- Table Body -->
 <tbody>
  <?php foreach ($produk as $i => $p) : ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= esc($p['nama_penjual']) ?></td>
      <td><?= esc($p['nama_produk']) ?></td>
      <td><?= esc($p['nama_kategori']) ?></td>
      <td>Rp<?= number_format($p['harga'], 0, ',', '.') ?></td>
      <td><?= $p['stok'] ?></td>

      <!-- Tanggal Masuk dengan badge-success -->
      <td>
        <span class="badge badge-success">
          <?= date('d-m-Y', strtotime($p['in_date'])) ?>
        </span>
      </td>

      <!-- Tanggal Expired dengan badge-danger -->
      <td>
        <span class="badge badge-danger">
          <?= date('d-m-Y', strtotime($p['exp_date'])) ?>
        </span>
      </td>

      <!-- Gambar Produk -->
      <td>
        <img src="<?= base_url('uploads/produk/' . $p['gambar']) ?>" width="60" alt="gambar produk">
      </td>

      <!-- Aksi -->
      <td>
        <a href="<?= base_url('/produk/edit/' . $p['id']) ?>" class="btn btn-warning btn-sm">
          <i class="fas fa-edit"></i>
        </a>
        <a href="<?= base_url('/produk/delete/' . $p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
          <i class="fas fa-trash"></i>
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<!-- DataTables Script -->
<script> 
  document.addEventListener("DOMContentLoaded", function () { 
    $('#productTable').DataTable({ 
      pageLength: 5, 
      responsive: true, 
      autoWidth: false, 
      dom: 'Bfrtip', // Ini bagian penting! 
      buttons: [ 
      { extend: 'copy', text: 'Salin' }, 
      { extend: 'excel', text: 'Unduh Excel' }, 
      { extend: 'pdf', text: 'Unduh PDF' }, 
      { extend: 'print', text: 'Cetak' } 
      ], 
      language: { 
        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" 
      } 
    }); 
  }); 
</script> 

<?= $this->endSection() ?>
