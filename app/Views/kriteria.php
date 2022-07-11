<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<?php
$err = session()->getFlashdata('validation');
?>

<h4><i class="fas fa-list"></i> Data Kriteria</h4>
<hr />
<div class="row">
    <div class="col-md-4 col-sm-12">
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

        if (session()->getFlashdata('url_update')) {
            echo '<h5 id="form-header">Update Data</h5>';
            echo '<form action="' . session()->getFlashdata('url_update') . '" method="POST" id="form-kriteria">';
        } else {
            echo '<h5 id="form-header">Tambah Data</h5>';
            echo '<form action="' . base_url('kriteria') . '" method="POST" id="form-kriteria">';
        }
        ?>
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

        <div class="form-group my-1">
            <label for="kode" class="py-2">Kode Kriteria</label>
            <input type="text" name="kode" id="kode" class="form-control form-control-sm <?= (isset($err['kode'])) ? 'is-invalid' : ''; ?>" placeholder="Kode Kriteria..." value="<?= old('kode'); ?>" required>
            <div class="invalid-feedback">
                <?= (isset($err['kode'])) ? $err['kode'] : ''; ?>
            </div>
        </div>

        <div class="form-group my-1">
            <label for="judul" class="py-2">Judul Kriteria</label>
            <input type="text" name="judul" id="judul" class="form-control form-control-sm <?= (isset($err['judul'])) ? 'is-invalid' : ''; ?>" placeholder="Judul Kriteria..." value="<?= old('judul'); ?>" required>
            <div class="invalid-feedback">
                <?= (isset($err['judul'])) ? $err['judul'] : ''; ?>
            </div>
        </div>

        <div class="form-group my-1">
            <label for="sifat" class="py-2">Sifat Kriteria</label>
            <select name="sifat" id="sifat" class="form-control form-control-sm <?= (isset($err['sifat'])) ? 'is-invalid' : ''; ?>">
                <option value="" disabled selected>Pilih Sifat Kriteria</option>
                <option value="cost" <?= (old('sifat') == 'cost') ? 'selected' : ''; ?>>Cost</option>
                <option value="benefit" <?= (old('sifat') == 'benefit') ? 'selected' : ''; ?>>Benefit</option>
            </select>
            <div class="invalid-feedback">
                <?= (isset($err['sifat'])) ? $err['sifat'] : ''; ?>
            </div>
        </div>

        <div class="form-group my-1 py-2 text-end">
            <button type="submit" name="Submit" value="Submit" class="btn btn-sm btn-success">
                Simpan Data
            </button>
        </div>
        </form>
    </div>
    <div class="col-md-8 col-sm-12">
        <h5>List Kriteria</h5>
        <div class="table-responsive-sm">
            <table id="table" class="table table-striped text-white">
                <thead>
                    <tr>
                        <th style="min-width: 50px;">No.</th>
                        <th style="min-width: 100;">Kode Kriteria</th>
                        <th style="min-width: 200;">Judul Kriteria</th>
                        <th style="min-width: 100;">Sifat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>