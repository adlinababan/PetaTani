<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<section class="content">
  <div class="container-fluid">
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Kategori</h3>
        <a href="<?= base_url('/kategori/create') ?>" class="btn btn-primary btn-sm">
          <i class="fas fa-plus-circle"></i> Tambah Kategori
        </a>
      </div>

      <div class="card-body">
        <table id="categoryTable" class="table table-bordered table-hover text-nowrap text-center mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Kategori</th>
              <th>Slug</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($kategori as $i => $k) : ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($k['nama_kategori']) ?></td>
                <td><?= esc($k['slug']) ?></td>
                <td>
                  <a href="<?= base_url('/kategori/edit/' . $k['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                  <a href="<?= base_url('/kategori/delete/' . $k['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?')"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<!-- DataTables Script -->
<script> 
  document.addEventListener("DOMContentLoaded", function () { 
    $('#categoryTable').DataTable({ 
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