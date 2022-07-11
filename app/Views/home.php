<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<h4><i class="fas fa-home"></i> Home</h4>
<hr />
<div class="text-center p-3">
    <h5>Selamat Datang di SPK dengan metode <b style="color: #2baf30">Simple Additive Weighting</b></h5>
    <p class="lead">Saat ini anda login sebagai <b style="color: #2baf30"><?= session()->get('user'); ?></b> dengan level <b style="color: #2baf30"><?= session()->get('role'); ?></b></p>
</div>
<?= $this->endSection(); ?>