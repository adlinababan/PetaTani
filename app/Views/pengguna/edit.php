<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<section class="content">
  <div class="container-fluid">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit Pengguna</h3>
      </div>

      <form action="<?= base_url('/pengguna/update/' . $pengguna['id']) ?>" method="post">
        <div class="card-body">
          <div class="form-group">
            <label>Nama Pengguna</label>
            <input type="text" name="name" class="form-control" value="<?= esc($pengguna['name']) ?>" required>
          </div>
		  
		  <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control" value="<?= esc($pengguna['email']) ?>" required>
          </div>
		  
		  <div class="form-group">
            <label>Grup</label>
			<br>
            <select id="kode_group" name="kode_group"  style="padding:10.5px; width:100%; font-size:13px; margin-top:5px; border:1px solid #ccc; border-radius:3px;">
				<?php
					foreach($list_group as $rec)
					{
						$selected = ($rec->kode_group == $pengguna['kode_group']) ? 'selected' : '';
						
						echo '
							<option value="'.$rec->kode_group.'" '.$selected.'>'.$rec->nama_group.'</option>
						';
					}
				?>
			</select>
          </div>
		  
		   <div class="form-group">
            <label>Password</label>
            <input type="password" value="<?php echo md5('123'); ?>" class="form-control" disabled>
			
			<div style="color:gray; margin-top:5px; font-style:italic;">
				Secara default password pengguna adalah '123'
			</div>
          </div>
        </div>
		
        <div class="card-footer">
          <button type="submit" class="btn btn-warning">Update</button>
          <a href="<?= base_url('/pengguna') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>