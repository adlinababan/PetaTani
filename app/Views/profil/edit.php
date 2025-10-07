<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php 
$session = \Config\Services::session();
?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Profil</h3>
      </div>

      <form action="<?= base_url('/profil/update/' .$session->get('user_id')) ?>" method="post">
        <div class="card-body">
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="<?= $data[0]->name ?>" class="form-control" required>
          </div>
		  
		  <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="<?= $data[0]->email ?>" class="form-control" required>
          </div>
		  
		   <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password" class="form-control">
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update</button>
          <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>