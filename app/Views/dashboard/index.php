<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php 
$session = \Config\Services::session();
?>

<section class="content">
 <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>      
<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
		  <?php if($session->get('group_code') == 'ADM') { ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
  <div class="inner">
     <h3><?= number_format($info[0]->info1, 0, ".", ","); ?></h3>
    <p>Total Produk</p>
  </div>
  <div class="icon">
    <i class="fas fa-boxes"></i> <!-- Ganti dengan ikon sesuai preferensi -->
  </div>
  <a href="<?= base_url('/produk') ?>" class="small-box-footer">
    More info <i class="fas fa-arrow-circle-right"></i>
  </a>
</div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= number_format($info[0]->info2, 0, ".", ","); ?></h3>
                <p>Kategori Produk</p>
              </div>
              <div class="icon">
                <i class="fas fa-box"></i>
              </div>
              <a href="<?= base_url('/kategori') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= number_format($info[0]->info3, 0, ".", ","); ?></h3>
                <p>Pembayaran Berhasil</p>
              </div>
              <div class="icon">
                <i class="fas fa-check"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= number_format($info[0]->info4, 0, ".", ","); ?></h3>
              <p>Pembayaran Pending</p>
              </div>
              <div class="icon">
                <i class="fas fa-hourglass"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
		  <?php } else { ?>
		  
		  <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
  <div class="inner">
     <h3><?= number_format($info[0]->info1, 0, ".", ","); ?></h3>
    <p>Total Produk</p>
  </div>
  <div class="icon">
    <i class="fas fa-boxes"></i> <!-- Ganti dengan ikon sesuai preferensi -->
  </div>
  <a href="<?= base_url('/produk') ?>" class="small-box-footer">
    More info <i class="fas fa-arrow-circle-right"></i>
  </a>
</div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= number_format($info[0]->info2, 0, ".", ","); ?></h3>
                <p>Kategori Produk</p>
              </div>
              <div class="icon">
                <i class="fas fa-box"></i>
              </div>
              <a href="<?= base_url('/kategori') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= number_format($info[0]->info3, 0, ".", ","); ?></h3>
                <p>Pembayaran Berhasil</p>
              </div>
              <div class="icon">
                <i class="fas fa-check"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= number_format($info[0]->info4, 0, ".", ","); ?></h3>
                <p>Pembayaran Pending</p>
              </div>
              <div class="icon">
                <i class="fas fa-hourglass"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
		  
		  <?php } ?>
          <!-- ./col -->
        </div>
        <!-- /.row -->
    </section>

<?= $this->endSection() ?>
