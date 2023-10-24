<?= $this->extend('layouts/auth/login'); ?>

<?= $this->section('content'); ?>
<?php

if (session()->getFlashdata('error')) { ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php } ?>
<form class="row g-3 needs-validation" validate method="POST" action="<?= base_url('auth') ?>">
  <div class="col-12">
    <label for="yourUsername" class="form-label">Email</label>
    <div class="input-group has-validation">
      <input type="email" name="email" class="form-control" id="yourUsername" required>
      <div class="invalid-feedback">Please enter your username.</div>
    </div>
  </div>

  <div class="col-12">
    <label for="yourPassword" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="yourPassword" required>
    <div class="invalid-feedback">Please enter your password!</div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary w-100" type="submit">Login</button>
  </div>
  <br>
</form>

<?= $this->endSection(); ?>