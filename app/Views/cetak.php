<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            vertical-align: middle;
        }

        td,
        th {
            border: 1px solid #000000;
            padding: 2px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <h4 class="text-center">SPK Metode SAW</h4>
        <hr />
        <h5 class="text-center">Tabel Kecocokan</h5>
        <div class="my-1">
            <table class="table table-striped table-bordered">
                <?= $data_analisa1; ?>
            </table>
        </div>
        <div class="my-1">
            <table class="table table-striped table-bordered">
                <?= $data_analisa2; ?>
            </table>
        </div>


        <h5 class="text-center">Tabel Normalisasi</h5>
        <div class="my-1">
            <table class="table table-striped table-bordered">
                <?= $data_normalisasi; ?>
            </table>
        </div>


        <h5 class="text-center">Tabel Bobot Kriteria</h5>
        <div class="my-1">
            <table class="table table-striped table-bordered">
                <?= $data_bobot; ?>
            </table>
        </div>

        <h5 class="text-center">Tabel Perangkingan</h5>
        <div class="my-1">
            <table class="table table-striped table-bordered">
                <?= $data_perangkingan; ?>
            </table>
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
        Dari hasil perangkingan diatas, Alternative terbaik jatuh pada <b><?= trim($pemenang, ', &'); ?></b> dengan perolehan nilai <b><?= $hasil['hasil']; ?></b>
    </div>
</body>

</html>