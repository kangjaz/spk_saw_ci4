<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>
<h4><i class="fas fa-balance-scale"></i> Bobot</h4>
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
<?= form_open('/bobot'); ?>
<?= $form; ?>
<div class="form-group row mb-3">
    <div class="col-sm-12 col-md-4 offset-md-3">
        <button type="submit" name="Submit" value="Submit" class="btn btn-primary">Simpan Data</button>
    </div>
</div>
<?= form_close(); ?>
<?= $this->endSection(); ?>