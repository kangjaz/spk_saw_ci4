<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<?php
$err = session()->getFlashdata('validation');
?>

<h5><i class="fas fa-user"></i> Profil</h5>
<hr />
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> Harap masukkan password untuk menyimpan semua perubahan
</div>
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
<?= form_open('/profil'); ?>
<div class="form-group row mb-3">
    <label class="col-sm-12 col-md-3 col-form-label right-label">Username</label>
    <div class="col-sm-12 col-md-5">
        <input type="text" class="form-control <?= (isset($err['user'])) ? 'is-invalid' : ''; ?>" id="user" name="user" value="<?= (old('user')) ? old('user') : $data['username']; ?>" placeholder="Username..." required>
        <div class="invalid-feedback">
            <?= (isset($err['user'])) ? $err['user'] : ''; ?>
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-12 col-md-3 col-form-label right-label">Fullname</label>
    <div class="col-sm-12 col-md-5">
        <input type="text" class="form-control <?= (isset($err['nama'])) ? 'is-invalid' : ''; ?>" id="nama" name="nama" value="<?= (old('nama')) ? old('nama') : $data['fullname']; ?>" placeholder="Fullname..." required>
        <div class="invalid-feedback">
            <?= (isset($err['nama'])) ? $err['nama'] : ''; ?>
        </div>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-12 col-md-3 col-form-label right-label">Password</label>
    <div class="col-sm-12 col-md-5">
        <input type="password" class="form-control <?= (isset($err['password'])) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password..." required>
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