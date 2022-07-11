<?= $this->extend('dashboard'); ?>

<?= $this->section('content'); ?>
<h4><i class="fas fa-clipboard-check"></i> Hasil Perhitungan</h4>
<hr />
<?php
if (isset($alert)) :
    echo '<div class="alert alert-danger">' . $alert . '</div>';
else :
?>
    <div class="text-end mb-3">
        <a href="<?= base_url('cetak-hasil'); ?>" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print"></i> Cetak</a>
    </div>
    <div class="card bg-dark text-white mb-3">
        <div class="card-header text-center bg-primary">Tabel Kecocokan</div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-striped table-bordered">
                    <?= $data_analisa1; ?>
                </table>
            </div>
            <div class="table-responsive-sm mt-2">
                <table class="table table-striped table-bordered">
                    <?= $data_analisa2; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="card bg-dark text-white mb-3">
        <div class="card-header text-center bg-primary">Tabel Normalisasi</div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-striped table-bordered">
                    <?= $data_normalisasi; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="card bg-dark text-white mb-3">
        <div class="card-header text-center bg-primary">Tabel Bobot Kriteria</div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-striped table-bordered">
                    <?= $data_bobot; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="card bg-dark text-white mb-3">
        <div class="card-header text-center bg-primary">Tabel Perangkingan</div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-striped table-bordered">
                    <?= $data_perangkingan; ?>
                </table>
            </div>
        </div>
    </div>
    <?php
    $hasil = $get->getRowArray();
    $pemenang = '';
    $no = 1;
    if ($get->getNumRows() > 1) {
        foreach ($get->getResult() as $k) {
            if ($no == ($get->getNumRows() - 1)) {
                $pemenang .= ' & ' . $k->nama_alternative;
            } else {
                $pemenang .= ', ' . $k->nama_alternative;
            }
        }
    } else {
        $pemenang .= $hasil['nama_alternative'];
    }
    ?>
    <div class="alert alert-success">
        Dari hasil perangkingan diatas, Alternative terbaik jatuh pada <b><?= trim($pemenang, ', &'); ?></b> dengan perolehan nilai <b><?= $hasil['hasil']; ?></b>
    </div>
<?php endif; ?>

<?= $this->endSection(); ?>