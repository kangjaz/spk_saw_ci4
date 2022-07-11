<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<?php
$err = session()->getFlashdata('validation');
?>

<h4><i class="fas fa-users"></i> Data Alternative</h4>
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
            echo '<form action="' . session()->getFlashdata('url_update') . '" method="POST" id="form-alternative">';
        } else {
            echo '<h5 id="form-header">Tambah Data</h5>';
            echo '<form action="' . base_url('alternative') . '" method="POST" id="form-alternative">';
        }
        ?>
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="form-group my-1">
            <label for="kode" class="py-2">Kode Alternative</label>
            <input type="text" name="kode" id="kode" class="form-control form-control-sm <?= (isset($err['kode'])) ? 'is-invalid' : ''; ?>" placeholder="Kode Alternative..." value="<?= old('kode'); ?>" required>
            <div class="invalid-feedback">
                <?= (isset($err['kode'])) ? $err['kode'] : ''; ?>
            </div>
        </div>
        <div class="form-group my-1">
            <label for="nama" class="py-2">Nama Alternative</label>
            <input type="text" name="nama" id="nama" class="form-control form-control-sm <?= (isset($err['nama'])) ? 'is-invalid' : ''; ?>" placeholder="Nama Alternative..." value="<?= old('nama'); ?>" required>
            <div class="invalid-feedback">
                <?= (isset($err['nama'])) ? $err['nama'] : ''; ?>
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
        <h5>List Alternative</h5>
        <div class="table-responsive-sm">
            <table id="table" class="table table-striped text-white">
                <thead>
                    <tr>
                        <th style="min-width: 50px;">No.</th>
                        <th style="min-width: 150px;">Kode Alternative</th>
                        <th style="min-width: 200px;">Nama Alternative</th>
                        <th style="max-width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>