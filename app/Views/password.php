<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<?php
$err = session()->getFlashdata('validation');
?>

<h5><i class="fas fa-lock"></i> Ganti Password</h5>
<hr />
<?php
//tampilkan pesan success
if (session()->getFlashdata('success')) {
    echo '<div class="my-2">
            <div class="alert alert-success alert-message ">' . session()->getFlashdata('success') . '</div>
            </div>';
}

//tampilkan pesan failed
if (session()->getFlashdata('failed')) {
    echo '<div class="my-2">
            <div class="alert alert-danger alert-message ">' . session()->getFlashdata('failed') . '</div>
            </div>';
}
?>
<?= form_open('/change-password'); ?>


<div class="form-group row mb-3">
    <label class="col-sm-12 col-md-3 col-form-label right-label">Password Baru</label>
    <div class="col-sm-12 col-md-5">
        <input type="password" class="form-control <?= (isset($err['password_baru'])) ? 'is-invalid' : ''; ?>" id="password_baru" name="password_baru" placeholder="Password Baru..." required>
        <div class="invalid-feedback">
            <?= (isset($err['password_baru'])) ? $err['password_baru'] : ''; ?>
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-12 col-md-3 col-form-label right-label">Retype Password Baru</label>
    <div class="col-sm-12 col-md-5">
        <input type="password" class="form-control <?= (isset($err['password_baru2'])) ? 'is-invalid' : ''; ?>" id="password_baru2" name="password_baru2" placeholder="Masukkan Ulang Password Baru..." required>
        <div class="invalid-feedback">
            <?= (isset($err['password_baru2'])) ? $err['password_baru2'] : ''; ?>
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-12 col-md-3 col-form-label right-label">Password Lama</label>
    <div class="col-sm-12 col-md-5">
        <input type="password" class="form-control <?= (isset($err['password'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password Lama..." required>
        <div class="invalid-feedback">
            <?= (isset($err['password'])) ? $err['password'] : ''; ?>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12 col-md-5 offset-md-3">
        <button type="submit" name="Submit" value="Submit" class="btn btn-success">Simpan Perubahan</button>
    </div>
</div>

<?= form_close(); ?>

<?= $this->endSection(); ?>