<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $session = \Config\Services::session(); ?>

<section class="content">
  <div class="container-fluid">

    <!-- Flash message -->
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <div class="card">

      <!-- Header (tampilkan tombol ekspor jika data tersedia) -->
      <?php if (!empty($data)) : ?>
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="card-title">Daftar Penjualan</h3>
          <a href="#" class="btn btn-success" onclick="export_excel()">
            <i class="fas fa-arrow-alt-circle-down"></i> Ekspor Excel
          </a>
        </div>
      <?php endif; ?>

      <!-- Form Filter -->
      <div class="card-header">
        <form method="post" style="width:100%;">
          <div class="row">
            <div class="col-md-2">
              <label>Tanggal (Awal)</label>
              <input type="date" id="srcstart" name="srcstart" class="form-control" value="<?= !empty($srcstart) ? $srcstart : date('Y-m-d') ?>">
            </div>
            <div class="col-md-2">
              <label>Tanggal (Akhir)</label>
              <input type="date" id="srcend" name="srcend" class="form-control" value="<?= !empty($srcend) ? $srcend : date('Y-m-t') ?>">
            </div>
            <div class="col-md-2">
              <label>Status</label>
              <select id="srcstatus" name="srcstatus" class="form-control">
                <option value="all" <?= ($srcstatus == 'all') ? 'selected' : '' ?>>All</option>
                <option value="paid" <?= ($srcstatus == 'paid') ? 'selected' : '' ?>>Paid</option>
                <option value="pending" <?= ($srcstatus == 'pending') ? 'selected' : '' ?>>Pending</option>
              </select>
            </div>

            <?php if ($session->get('group_code') == 'ADM') : ?>
              <div class="col-md-3">
                <label>Nama Penjual</label>
                <select id="srcseller" name="srcseller" class="form-control">
                  <option value="">All</option>
                  <?php foreach ($list_seller as $rec) :
                    $selected = ($rec->id == $srcseller) ? "selected" : "";
                  ?>
                    <option value="<?= $rec->id ?>" <?= $selected ?>><?= $rec->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>

            <div class="col-md-2 align-self-end">
              <button type="submit" class="btn btn-primary w-100 mt-2">
                Cari
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Table Data -->
      <div class="card-body">
        <div class="table-responsive">
        <table id="salesTable" class="table table-bordered table-hover text-center text-nowrap">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal Transaksi</th>
              <th>Nama Penjual</th>
              <th>Nama Customer</th>
              <th>No. HP</th>
              <th>Alamat</th>
              <th>Status</th>
              <th>Produk</th>
              <th>Qty</th>
              <th>Harga Satuan (Rp)</th>
              <th>Satuan</th>
              <th>Total (Rp)</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            foreach ($data as $rec) :
              $status = match ($rec->status) {
                'pending' => '<div class="btn btn-warning btn-sm text-white">PENDING</div>',
                'paid'    => '<div class="btn btn-success btn-sm text-white">PAID</div>',
                default   => '<div class="btn btn-danger btn-sm text-white">UNPAID</div>'
              };

              $qty = $rec->amount / $rec->harga;
              $harga_satuan = $rec->amount / $qty;
              $total = $rec->amount;
            ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= $rec->created_at ?></td>
                <td><?= esc($rec->nama_penjual) ?></td>
                <td><?= esc($rec->name) ?></td>
                <td><?= esc($rec->phone) ?></td>
                <td class="text-left"><?= esc($rec->address) ?></td>
                <td><?= $status ?></td>
                <td><?= esc($rec->nama_produk) ?></td>
                <td><?= $qty ?></td>
                <td class="text-right"><?= number_format($harga_satuan, 2, ".", ",") ?></td>
                <td><?= esc($rec->satuan) ?></td>
                <td class="text-right"><?= number_format($total, 2, ".", ",") ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    </div>
  </div>
</section>

<!-- Script Ekspor Excel -->
<script>
  function export_excel() {
    const srcstart = document.getElementById('srcstart').value;
    const srcend = document.getElementById('srcend').value;
    const srcstatus = document.getElementById('srcstatus').value;
    const srcseller = ('<?= $session->get('group_code') ?>' == 'ADM') ?
      document.getElementById('srcseller').value :
      '<?= $session->get('user_id') ?>';

    window.location.href = '<?= base_url('/salesreportexcel') ?>' +
      '?start=' + srcstart +
      '&end=' + srcend +
      '&status=' + srcstatus +
      '&seller=' + srcseller;
  }
</script>

<!-- DataTables Initialization -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    $('#salesTable').DataTable({
      pageLength: 5,
      responsive: true,
      autoWidth: false,
      dom: 'Bfrtip',
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
