<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>



<?php
  if (!function_exists('rupiah')) {
      function rupiah($angka) { return 'Rp' . number_format((float)$angka, 0, ',', '.'); }
  }
?>

<section class="content">
  <div class="container-fluid">

    <!-- Filter & Pencarian -->
    <div class="row mb-3">
      <div class="col-md-4">
        <form method="get" action="<?= base_url('/produk/detail').'?q='.$keyword.'&kategori='.$selected_kategori.'&seller='.$selected_seller ?>">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari produk..." value="<?= esc($keyword ?? '') ?>">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-4">
        <form method="get" action="<?= base_url('/produk/detail').'?q='.$keyword.'&kategori='.$selected_kategori.'&seller='.$selected_seller ?>">
          <select name="kategori" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori as $k) : ?>
              <option value="<?= $k['id'] ?>" <?= ($selected_kategori ?? '') == $k['id'] ? 'selected' : '' ?>>
                <?= esc($k['nama_kategori']) ?>
              </option>
            <?php endforeach ?>
          </select>
        </form>
      </div>
	   <div class="col-md-4">
        <form method="get" action="<?= base_url('/produk/detail').'?q='.$keyword.'&kategori='.$selected_kategori.'&seller='.$selected_seller ?>">
          <select name="seller" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Penjual</option>
			<?php foreach ($list_seller as $k) { ?>
				<option value="<?=$k->id?>" <?= ($selected_seller ?? '') == $k->id ? 'selected' : '' ?>><?=$k->name?></option>
			<?php } ?>
          </select>
        </form>
      </div>
    </div>

     <!-- Grid Produk -->
<div class="row">
  <?php if (empty($produk)) : ?>
    <div class="col-12">
      <div class="alert alert-warning text-center">Produk tidak ditemukan.</div>
    </div>
  <?php endif; ?>

     <div class="row">
  <?php foreach ($produk as $p) : 
    $exp_date = date('Y-m-d', strtotime($p['exp_date']));
    $today = date('Y-m-d');
    $is_expired_today = ($exp_date === $today);
    $is_expired_past = ($exp_date < $today);
    $is_out_of_stock = ($p['stok'] <= 0);
  ?>
    <div class="col-md-3 mb-4">
      <div class="card card-outline card-success h-100 d-flex flex-column shadow-sm">
        <img src="<?= base_url('uploads/produk/' . $p['gambar']) ?>"
             class="card-img-top"
             alt="<?= esc($p['nama_produk']) ?>"
             style="height: 180px; object-fit: cover;">

        <div class="card-body d-flex flex-column">
          <h5 class="card-title text-truncate"><?= esc($p['nama_produk']) ?></h5>
          <p class="mb-1">Harga: <strong><?= rupiah($p['harga']) ?></strong> / <?= esc($p['satuan']) ?></p>
          <p class="text-muted mb-2"><i class="fas fa-tags"></i> <?= esc($p['nama_kategori']) ?></p>

          <!-- Info Tambahan -->
          <div class="bg-light rounded px-2 py-2 mb-2 small border">
            <div class="d-flex justify-content-between">
              <span><strong>Stok:</strong></span>
              <span><?= (int)$p['stok'] ?> <?= esc($p['satuan']) ?></span>
            </div>
             <div class="d-flex justify-content-between align-items-center mb-1">
  <span><strong>Tgl Masuk:</strong></span>
  <span class="badge bg-success text-white"><?= date('d-m-Y', strtotime($p['in_date'])) ?></span>
</div>
<div class="d-flex justify-content-between align-items-center">
  <span><strong>Expired:</strong></span>
  <span class="badge bg-danger text-white"><?= date('d-m-Y', strtotime($p['exp_date'])) ?></span>
</div>
          </div>

          <!-- Seller -->
          <p class="small text-muted mb-3 p-2 bg-white border rounded text-truncate">
            <i class="fas fa-store-alt"></i> <?= esc($p['nama_penjual']) ?>
          </p>

          <!-- Aksi -->
          <div class="mt-auto">
            <a href="<?= $p['link_wa'] ?>" class="btn btn-outline-success btn-sm w-100 mb-2" target="_blank">
              <i class="fab fa-whatsapp"></i> Pesan via WA
            </a>

            <?php if ($is_expired_today): ?>
              <div class="alert alert-warning text-center small py-2">
                <i class="fas fa-exclamation-triangle"></i> Produk expired hari ini
              </div>
              <button class="btn btn-secondary btn-sm w-100" disabled>
                <i class="fas fa-ban"></i> Tidak Tersedia
              </button>

            <?php elseif ($is_expired_past || $is_out_of_stock): ?>
              <button class="btn btn-secondary btn-sm w-100" disabled>
                <i class="fas fa-ban"></i> Tidak Tersedia
              </button>

            <?php else: ?>
              <button 
                type="button"
                class="btn btn-primary btn-sm w-100 btn-beli"
                data-id="<?= (int)($p['id'] ?? 0) ?>"
                data-nama="<?= esc($p['nama_produk']) ?>"
                data-harga="<?= (int)$p['harga'] ?>"
                data-stok="<?= (int)$p['stok'] ?>"
              >
                <i class="fas fa-shopping-cart"></i> Beli Sekarang
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>

</section>

<!-- MODAL QUICK CHECKOUT -->
<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog" aria-labelledby="modalCheckoutLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="checkoutForm" method="post" action="<?= base_url('/checkout/create') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="product_id" id="product_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Checkout — <span id="produkNama">Produk</span>
          </h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <!-- Ringkasan -->
          <div class="row">
            <div class="col-md-4">
              <div class="form-group mb-2">
                <label>Harga</label>
                <input type="text" class="form-control" id="hargaText" disabled>
              </div>
            </div>
            <div class="col-md-4">
               <div class="form-group mb-2">
              <label>Kuantitas</label>
              <input type="number" class="form-control" id="qty" name="qty" min="1" value="1" required>
              <div class="invalid-feedback">
                Kuantitas melebihi stok tersedia.
              </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group mb-2">
                <label>Grand Total</label>
                <input type="text" class="form-control" id="grandText" disabled>
              </div>
            </div>
          </div>

          <hr>

          <!-- Billing (WAJIB) -->
          <div class="row">
            <div class="col-md-6">
              <h6 class="mb-2">Data Pembeli (Billing)</h6>
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="billing_name" required placeholder="Nama sesuai tagihan">
              </div>
              <div class="form-group">
                <label>Email (untuk e‑receipt)</label>
                <input type="email" class="form-control" name="email" required placeholder="you@example.com">
              </div>
              <div class="form-group">
                <label>No. HP / WhatsApp</label>
                <input type="text" class="form-control" name="phone" required placeholder="+62...">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="billing_address" rows="3" required placeholder="Nama jalan, RT/RW, dsb."></textarea>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Kota/Kabupaten</label>
                  <input type="text" class="form-control" name="billing_city" required>
                </div>
                <div class="form-group col-md-6">
                  <label>Provinsi</label>
                  <input type="text" class="form-control" name="billing_state" required>
                </div>
              </div>
              <div class="form-group">
                <label>Kode Pos</label>
                <input type="text" class="form-control" name="billing_zip" required>
              </div>
            </div>

            <!-- Shipping opsional (tanpa ongkir) -->
            <div class="col-md-6">
              <h6 class="mb-2">
                Alamat Shipping 
                <small class="text-muted">(opsional, tanpa ongkir)</small>
              </h6>
              <div class="form-group form-check mb-2">
                <input type="checkbox" class="form-check-input" id="sameAsBilling" checked>
                <label class="form-check-label" for="sameAsBilling">Sama dengan Billing</label>
              </div>
              <div class="form-group">
                <label>Nama Penerima</label>
                <input type="text" class="form-control" name="shipping_name" placeholder="Nama penerima">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="shipping_address" rows="3" placeholder="Alamat pengiriman / catatan"></textarea>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Kota/Kabupaten</label>
                  <input type="text" class="form-control" name="shipping_city">
                </div>
                <div class="form-group col-md-6">
                  <label>Provinsi</label>
                  <input type="text" class="form-control"name="shipping_state">
                </div>
              </div>
              <div class="form-group">
                <label>Kode Pos</label>
                <input type="text" class="form-control" name="shipping_zip">
              </div>
              <small class="text-muted">Jika produk digital, bagian ini sekadar catatan.</small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <div class="mr-auto text-muted">
            Pembayaran diproses oleh <strong>Duitku</strong>. Setelah klik “Checkout”, Anda akan diarahkan ke halaman pembayaran.
          </div>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Checkout
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

 <script>
  let hargaAktif = 0;
  let stokAktif = 0;

  function formatRupiah(n) {
    n = Math.max(0, Math.round(n));
    return 'Rp' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function recalcGrand() {
    const qtyEl = document.getElementById('qty');
    const grandText = document.getElementById('grandText');
    const checkoutBtn = document.querySelector('#checkoutForm button[type="submit"]');

    const qty = Math.max(1, parseInt(qtyEl.value || '1', 10));
    const grand = hargaAktif * qty;
    grandText.value = formatRupiah(grand);

    if (qty > stokAktif) {
      qtyEl.classList.add('is-invalid');
      if (checkoutBtn) checkoutBtn.disabled = true;
    } else {
      qtyEl.classList.remove('is-invalid');
      if (checkoutBtn) checkoutBtn.disabled = false;
    }
  }

  function openModalCheckout() {
    const modalEl = document.getElementById('modalCheckout');
    if (window.bootstrap && bootstrap.Modal) {
      const m = bootstrap.Modal.getOrCreateInstance(modalEl);
      m.show();
      return;
    }
    if (window.$ && typeof $(modalEl).modal === 'function') {
      $(modalEl).modal('show');
      return;
    }
    modalEl.style.display = 'block';
    modalEl.classList.add('show');
    modalEl.removeAttribute('aria-hidden');
  }

  // Tombol "Beli Sekarang"
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-beli');
    if (!btn) return;

    const product_id = btn.getAttribute('data-id') || '';
    const nama = btn.getAttribute('data-nama') || 'Produk';
    const harga = parseInt(btn.getAttribute('data-harga') || '0', 10);
    const stok = parseInt(btn.getAttribute('data-stok') || '0', 10);

    hargaAktif = harga;
    stokAktif = stok;

    document.getElementById('product_id').value = product_id;
    document.getElementById('produkNama').textContent = nama;
    document.getElementById('hargaText').value = formatRupiah(harga);
    document.getElementById('qty').value = 1;
    document.getElementById('grandText').value = formatRupiah(harga);

    const qtyEl = document.getElementById('qty');
    qtyEl.classList.remove('is-invalid');
    document.querySelector('#checkoutForm button[type="submit"]').disabled = false;

    openModalCheckout();
  });

  // Hitung ulang grand total saat kuantitas diubah
  document.addEventListener('input', function(e) {
    if (e.target && e.target.id === 'qty') recalcGrand();
  });

  // Checkbox "sama dengan billing"
  function sameAsBillingChecked() { 
    const cb = document.getElementById('sameAsBilling'); 
    return cb && cb.checked; 
  }

  const mapFields = [
    ['billing_name','shipping_name'],
    ['billing_address','shipping_address'],
    ['billing_city','shipping_city'],
    ['billing_state','shipping_state'],
    ['billing_zip','shipping_zip'],
  ];

  function copyBilling() {
    if (!sameAsBillingChecked()) return;
    mapFields.forEach(([b, s]) => {
      const bEl = document.querySelector(`[name="${b}"]`);
      const sEl = document.querySelector(`[name="${s}"]`);
      if (bEl && sEl) sEl.value = bEl.value;
    });
  }

  document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'sameAsBilling') {
      if (sameAsBillingChecked()) copyBilling();
      mapFields.forEach(([_, s]) => {
        const sEl = document.querySelector(`[name="${s}"]`);
        if (sEl) sEl.required = false;
      });
    }
  });

  mapFields.forEach(([b, _]) => {
    document.addEventListener('input', function(e) {
      if (e.target && e.target.getAttribute('name') === b && sameAsBillingChecked()) {
        copyBilling();
      }
    });
  });
</script>

 <style>

  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.2; }
  }
</style>


<?= $this->endSection() ?>
