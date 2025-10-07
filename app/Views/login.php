<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>

<main class="form-signin w-100 m-auto" style="max-width: 330px;">
  <?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= base_url('do-login') ?>" method="post">
    <img class="mb-4" src="<?= base_url('assets/PetaTani.png') ?>" alt="" width="100" height="100">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating mb-2">
      <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
      <label for="floatingPassword">Password</label>
    </div>

    <button class="btn btn-success w-100 py-2" type="submit">Sign in</button>
  </form>
</main>

<?= $this->endSection() ?>
