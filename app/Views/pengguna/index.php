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
        <h3 class="card-title">Daftar Pengguna</h3>
        <a href="<?= base_url('/pengguna/create') ?>" class="btn btn-primary btn-sm">
          <i class="fas fa-plus-circle"></i> Tambah Pengguna
        </a>
      </div>

      <div class="card-body">
        <table id="usersTable" class="table table-bordered table-hover text-nowrap text-center mb-0">
          <thead>
            <tr>
              <th style="text-align:center;">No</th>
              <th style="text-align:center;">Nama Pengguna</th>
              <th style="text-align:center;">Grup</th>
              <th style="text-align:center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; foreach ($pengguna as $rec) { ?>
              <tr>
                <td style="text-align:center;"><?= $i++ ?></td>
                <td><?= $rec->name ?></td>
                <td style="text-align:center;"><?= $rec->nama_group ?></td>
                <td style="text-align:center;">
                  <a href="<?= base_url('/pengguna/edit/' . $rec->id) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                  <a href="<?= base_url('/pengguna/delete/' . $rec->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini?')"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<!-- DataTables Script -->
<script> 
  document.addEventListener("DOMContentLoaded", function () { 
    $('#usersTable').DataTable({ 
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