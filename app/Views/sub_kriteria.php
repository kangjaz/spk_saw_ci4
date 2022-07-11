<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>

<?php
$err = session()->getFlashdata('validation');
?>

<h4><i class="fas fa-list-alt"></i> Data Sub Kriteria</h4>
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
            echo '<form action="' . session()->getFlashdata('url_update') . '" method="POST" id="form-sub-kriteria">';
        } else {
            echo '<h5 id="form-header">Tambah Data</h5>';
            echo '<form action="' . base_url('sub-kriteria') . '" method="POST" id="form-sub-kriteria">';
        }
        ?>
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

        <div class="form-group my-1">
            <label for="kriteria" class="py-2">Kriteria</label>
            <select name="kriteria" id="kriteria" class="form-control form-control-sm <?= (isset($err['kriteria'])) ? 'is-invalid' : ''; ?>" required>
                <option value="" disabled selected>Pilih kriteria</option>
                <?php
                foreach ($kriteria as $k) {
                    $pilih = (old('kriteria') == $k['id_kriteria']) ? 'selected' : '';
                    echo '<option value="' . $k['id_kriteria'] . '" ' . $pilih . '>' . $k['judul_kriteria'] . '</option>';
                }
                ?>
            </select>
            <div class="invalid-feedback">
                <?= (isset($err['kriteria'])) ? $err['kriteria'] : ''; ?>
            </div>
        </div>

        <div class="form-group my-1">
            <label for="nilai" class="py-2">Nilai</label>
            <input type="number" name="nilai" id="nilai" class="form-control form-control-sm <?= (isset($err['nilai'])) ? 'is-invalid' : ''; ?>" placeholder="Nilai" step="0.01" required value="<?= old('nilai'); ?>">
            <div class="invalid-feedback">
                <?= (isset($err['nilai'])) ? $err['nilai'] : ''; ?>
            </div>
        </div>

        <div class="form-group my-1">
            <label for="keterangan" class="py-2">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm <?= (isset($err['keterangan'])) ? 'is-invalid' : ''; ?>" placeholder="Keterangan Sub Kriteria" required value="<?= old('keterangan'); ?>">
            <div class="invalid-feedback">
                <?= (isset($err['keterangan'])) ? $err['keterangan'] : ''; ?>
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
        <h5>List Sub Kriteria</h5>
        <div class="table-responsive-sm">
            <table id="table" class="table table-striped text-white">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kriteria</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
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