<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>
<h4><i class="fas fa-balance-scale-right"></i> Penilaian</h4>
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
            echo '<form action="' . session()->getFlashdata('url_update') . '" method="POST" id="form-penilaian">';
        } else {
            echo '<h5 id="form-header">Tambah Data</h5>';
            echo '<form action="' . base_url('penilaian') . '" method="POST" id="form-penilaian">';
        }
        ?>
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div id="body-form-penilaian">
            <?php
            if (session()->getFlashdata('alternative')) :
                echo session()->getFlashdata('alternative');
            else :
            ?>
                <div class="form-group mb-2">
                    <label>Alternative</label>
                    <select name="alternative" id="alternative" class="form-control form-control-sm" required>
                        <option value="" selected>Pilih Alternative</option>
                        <?php
                        foreach ($alternative as $a) {
                            $pilih = (old('alternative') == $a['id_alternative']) ? 'selected' : '';
                            echo '<option value="' . $a['id_alternative'] . '" ' . $pilih . '>' . $a['nama_alternative'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php endif; ?>
            <?= $form; ?>
        </div>
        <div class="form-group text-end py-2 mt-1">
            <button type="submit" name="Submit" value="Submit" class="btn btn-success btn-sm">
                Simpan Data
            </button>
        </div>
        </form>
    </div>
    <div class="col-md-8 col-sm-12">
        <h5>List Penilaian</h5>
        <div class="table-responsive-sm">
            <table id="table" class="table table-striped text-white">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Alternative</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($penilaian->getNumRows() < 1) {
                        echo '<tr><td colspan="3" class="text-center text-white">Belum ada data</td></tr>';
                    } else {
                        $no = 1;
                        foreach ($penilaian->getResult() as $p) {
                            echo '<tr class="text-white">';
                            echo '<td>' . $no++ . '</td>';
                            echo '<td>' . $p->nama_alternative . '</td>';
                            echo '<td>
                                <button type="button" onclick="detailPenilaian(\'' . encrypt_url($p->id_alternative) . '\')" class="btn btn-success btn-xs my-1"><i class="fas fa-search"></i> Detail</button>
                                <button type="button" onclick="getData(\'' . encrypt_url($p->id_alternative) . '\')" class="btn btn-warning btn-xs my-1"><i class="fas fa-edit"></i> Edit</button>
                                <button type="button" onclick="sweetDelete(\'' . encrypt_url($p->id_alternative) . '\')" class="btn btn-danger btn-xs my-1"><i class="fas fa-trash"></i> Hapus</button>
                            </td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="penilaian" tabindex="-1" aria-labelledby="penilaianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="penilaianModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="penilaianModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>