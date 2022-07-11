<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<h5><i class="fas fa-users"></i> Manajemen User</h5>
<hr />
<div class="text-end">
    <a href="<?= base_url('user/add'); ?>" class="btn btn-primary btn-sm">Tambah User</a>
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
<div class="table-responsive-sm">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <table id="table" class="table table-striped text-white">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Fullname</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<?= $this->endSection(); ?>