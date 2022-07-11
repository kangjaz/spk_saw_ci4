<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlternativeModel;
use App\Models\BobotModel;
use App\Models\HasilModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use TCPDF;

class Hasil extends BaseController
{
    public function index()
    {
        //load model kriteria
        $kriteria = new KriteriaModel();
        //load model alternative
        $alternative = new AlternativeModel();
        //load model penilaian
        $penilaian = new PenilaianModel();
        //load model hasil
        $model = new HasilModel();
        //load model bobot
        $bobotModel = new BobotModel();
        //cek bobot
        $cekBobot = $bobotModel->cekBobot();
        //cek penilaian
        $cekPenilaian = $penilaian->cekPenilaian();

        if ($cekBobot && $cekPenilaian) {
            //ambil data kriteria
            $getKriteria = $kriteria->getKriteria()->getResult();
            $judul_kriteria = '';
            $kode_kriteria = '';
            $bodyTableAnalisa2 = '';
            $bobot      = array();
            $data_arr   = array();
            $hasil_arr  = array();
            $nilai_bobot = array();

            foreach ($getKriteria as $k) {
                $judul_kriteria .= '<th style="min-width:80px">' . $k->judul_kriteria . '</th>';
                $kode_kriteria .= '<th style="min-width:80px">' . $k->kode_kriteria . '</th>';
                $bobot[$k->id_kriteria] = $k->nilai;
            }

            //tabel analisa
            $data_analisa1 = '<thead class="text-center align-middle">
                            <tr>
                                <th rowspan="2" style="min-width:50px">#</th>
                                <th rowspan="2" style="min-width:200px">Nama Alternative</th>
                                <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
                            </tr>
                            <tr>';
            $data_analisa1 .= $judul_kriteria;
            $data_analisa1 .= '</tr></thead>';
            $data_analisa1 .= '<tbody>';

            //ambil data alternative
            $getAlternative = $alternative->getAlternative();
            $no_analisa = 1;
            foreach ($getAlternative->getResult() as $a) {
                $data_analisa1 .= '<tr><td class="text-center">' . $no_analisa . '</td>
                                <td>' . $a->nama_alternative . '</td>';

                $bodyTableAnalisa2 .= '<tr><td class="text-center">' . $no_analisa . '</td>
                <td>' . $a->kode_alternative . '</td>';

                //ambil hasil penilaian
                $getNilai = $penilaian->getNilai($a->id_alternative);

                foreach ($getNilai->getResult() as $n) {
                    $data_analisa1 .= '<td class="text-center">' . $n->keterangan . '</td>';
                    $bodyTableAnalisa2 .= '<td class="text-center">' . $n->nilai . '</td>';
                }

                $data_analisa1 .= '</tr>';
                $bodyTableAnalisa2 .= '</tr>';

                $no_analisa++;
            }
            $data_analisa1 .= '</tbody>';

            $data_analisa2 = '<thead class="text-center align-middle">
                            <tr>
                                <th rowspan="2" style="min-width:50px">#</th>
                                <th rowspan="2" style="min-width:100px">Kode Alternative</th>
                                <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
                            </tr>
                            <tr>';
            $data_analisa2 .= $kode_kriteria;
            $data_analisa2 .= '</tr></thead>';
            $data_analisa2 .= '<tbody>';
            $data_analisa2 .= $bodyTableAnalisa2;
            $data_analisa2 .= '</tbody>';

            //table normalisasi
            $data_normalisasi = '<thead class="text-center align-middle">
        <tr>
            <th rowspan="2" style="min-width:50px">#</th>
            <th rowspan="2" style="min-width:200px">Kode Alternative</th>
            <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
        </tr>
        <tr>';
            $data_normalisasi .= $kode_kriteria;
            $data_normalisasi .= '</tr></thead>';
            $data_normalisasi .= '<tbody>';
            $no_normalisasi = 1;
            foreach ($getAlternative->getResult() as $a) {
                $data_normalisasi .= '<tr><td class="text-center">' . $no_normalisasi++ . '</td>
                                <td>' . $a->kode_alternative . '</td>';
                //ambil hasil penilaian
                $getNilai = $penilaian->getNilai($a->id_alternative);
                $no = 0;
                $total = 0;
                foreach ($getNilai->getResult() as $n) {
                    if ($n->nilai != null || $n->nilai != '') {
                        $data_normalisasi .= '<td class="text-center">' . $this->normalisasi($n->id_kriteria, $n->nilai) . '</td>';
                        //hitung total bobot
                        $bobotKriteria = $this->normalisasi($n->id_kriteria, $n->nilai) * $bobot[$n->id_kriteria];
                        $nilai_bobot[$a->id_alternative][$no++] = $bobotKriteria;
                        $total += $bobotKriteria;
                    } else {
                        $data_normalisasi .= '<td class="text-center"></td>';
                        $nilai_bobot[$a->id_alternative][$no++] = null;
                    }
                }

                //push total ke variabel data_arr
                array_push($data_arr, ['id_alternative' => $a->id_alternative, 'hasil' => $total]);
                $hasil_arr[$a->id_alternative] = $total;

                $data_normalisasi .= '</tr>';
            }

            //table bobot
            $data_bobot = '<thead class="text-center align-middle"><tr>';
            $data_bobot .= $kode_kriteria;
            $data_bobot .= '</tr></thead>';
            $data_bobot .= '<tbody>';
            $data_bobot .= '<tr>';
            foreach ($getKriteria as $k) {
                $data_bobot .= '<td class="text-center">' . $bobot[$k->id_kriteria] . '</td>';
            }
            $data_bobot .= '</tr>';
            $data_bobot .= '</tbody>';

            //table perangkingan
            $data_perangkingan = '<thead class="text-center align-middle">
        <tr>
            <th rowspan="2" style="min-width:50px">#</th>
            <th rowspan="2" style="min-width:200px">Kode Alternative</th>
            <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
            <th rowspan="2" style="min-width:80px">Hasil</th>
        </tr>
        <tr>';
            $data_perangkingan .= $kode_kriteria;
            $data_perangkingan .= '</tr></thead>';
            $data_perangkingan .= '<tbody>';
            $no_perangkingan = 1;
            foreach ($getAlternative->getResult() as $a) {
                $data_perangkingan .= '<tr><td class="text-center">' . $no_perangkingan++ . '</td>
                                <td>' . $a->kode_alternative . '</td>';

                foreach ($nilai_bobot[$a->id_alternative] as $b) {
                    $data_perangkingan .= '<td class="text-center">' . $b . '</td>';
                }

                $data_perangkingan .= '<td class="text-center">' . $hasil_arr[$a->id_alternative] . '</td>';
                $data_perangkingan .= '</tr>';
            }

            //simpan hasil perhitungan ke database
            $model->clearTable();
            $model->multiSave($data_arr);

            $get_data = $model->getWinner();

            $data = [
                'title'             => 'Hasil Perhitungan',
                'data_analisa1'      => $data_analisa1,
                'data_analisa2'      => $data_analisa2,
                'data_normalisasi'  => $data_normalisasi,
                'data_bobot'        => $data_bobot,
                'data_perangkingan' => $data_perangkingan,
                'get'               => $get_data
            ];
        } else {
            $data = [
                'title'      => 'Hasil Perhitungan',
                'alert'      => "Silahkan isi bobot dan penilaian terlebih dahulu",
            ];
        }

        return view('hasil', $data);
    }

    public function cetak()
    {
        //load model kriteria
        $kriteria = new KriteriaModel();
        //load model alternative
        $alternative = new AlternativeModel();
        //load model penilaian
        $penilaian = new PenilaianModel();
        //load model hasil
        $model = new HasilModel();
        //load model bobot
        $bobotModel = new BobotModel();
        //cek bobot
        $cekBobot = $bobotModel->cekBobot();
        //cek penilaian
        $cekPenilaian = $penilaian->cekPenilaian();

        if (!$cekBobot || !$cekPenilaian) {
            return redirect()->back();
        }

        $getKriteria = $kriteria->getKriteria()->getResult();
        $judul_kriteria = '';
        $kode_kriteria = '';
        $bodyTableAnalisa2 = '';
        $bobot      = array();
        $data_arr   = array();
        $hasil_arr  = array();
        $nilai_bobot = array();

        foreach ($getKriteria as $k) {
            $judul_kriteria .= '<th style="text-align:center">' . $k->judul_kriteria . '</th>';
            $kode_kriteria .= '<th style="text-align:center">' . $k->kode_kriteria . '</th>';
            $bobot[$k->id_kriteria] = $k->nilai;
        }

        //tabel analisa
        $data_analisa1 = '<thead>
                            <tr>
                                <th rowspan="2" style="text-align:center">#</th>
                                <th rowspan="2" style="text-align:center">Nama Alternative</th>
                                <th style="text-align:center" colspan="' . count($getKriteria) . '">Kriteria</th>
                            </tr>
                            <tr>';
        $data_analisa1 .= $judul_kriteria;
        $data_analisa1 .= '</tr></thead>';
        $data_analisa1 .= '<tbody>';

        //ambil data alternative
        $getAlternative = $alternative->getAlternative();
        $no_analisa = 1;
        foreach ($getAlternative->getResult() as $a) {
            $data_analisa1 .= '<tr><td class="text-center">' . $no_analisa . '</td>
                                <td>' . $a->nama_alternative . '</td>';

            $bodyTableAnalisa2 .= '<tr><td class="text-center">' . $no_analisa . '</td>
                <td>' . $a->kode_alternative . '</td>';

            //ambil hasil penilaian
            $getNilai = $penilaian->getNilai($a->id_alternative);

            foreach ($getNilai->getResult() as $n) {
                $data_analisa1 .= '<td class="text-center">' . $n->keterangan . '</td>';
                $bodyTableAnalisa2 .= '<td class="text-center">' . $n->nilai . '</td>';
            }

            $data_analisa1 .= '</tr>';
            $bodyTableAnalisa2 .= '</tr>';

            $no_analisa++;
        }
        $data_analisa1 .= '</tbody>';

        $data_analisa2 = '<thead class="text-center align-middle">
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Kode Alternative</th>
                                <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
                            </tr>
                            <tr>';
        $data_analisa2 .= $kode_kriteria;
        $data_analisa2 .= '</tr></thead>';
        $data_analisa2 .= '<tbody>';
        $data_analisa2 .= $bodyTableAnalisa2;
        $data_analisa2 .= '</tbody>';

        //table normalisasi
        $data_normalisasi = '<thead class="text-center align-middle">
        <tr>
            <th rowspan="2" style="min-width:50px">#</th>
            <th rowspan="2" style="min-width:200px">Kode Alternative</th>
            <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
        </tr>
        <tr>';
        $data_normalisasi .= $kode_kriteria;
        $data_normalisasi .= '</tr></thead>';
        $data_normalisasi .= '<tbody>';
        $no_normalisasi = 1;
        foreach ($getAlternative->getResult() as $a) {
            $data_normalisasi .= '<tr><td class="text-center">' . $no_normalisasi++ . '</td>
                                <td>' . $a->kode_alternative . '</td>';
            //ambil hasil penilaian
            $getNilai = $penilaian->getNilai($a->id_alternative);
            $no = 0;
            $total = 0;
            foreach ($getNilai->getResult() as $n) {
                if ($n->nilai != null || $n->nilai != '') {
                    $data_normalisasi .= '<td class="text-center">' . $this->normalisasi($n->id_kriteria, $n->nilai) . '</td>';
                    //hitung total bobot
                    $bobotKriteria = $this->normalisasi($n->id_kriteria, $n->nilai) * $bobot[$n->id_kriteria];
                    $nilai_bobot[$a->id_alternative][$no++] = $bobotKriteria;
                    $total += $bobotKriteria;
                } else {
                    $data_normalisasi .= '<td class="text-center"></td>';
                    $nilai_bobot[$a->id_alternative][$no++] = null;
                }
            }

            //push total ke variabel data_arr
            array_push($data_arr, ['id_alternative' => $a->id_alternative, 'hasil' => $total]);
            $hasil_arr[$a->id_alternative] = $total;

            $data_normalisasi .= '</tr>';
        }

        //table bobot
        $data_bobot = '<thead class="text-center align-middle"><tr>';
        $data_bobot .= $kode_kriteria;
        $data_bobot .= '</tr></thead>';
        $data_bobot .= '<tbody>';
        $data_bobot .= '<tr>';
        foreach ($getKriteria as $k) {
            $data_bobot .= '<td class="text-center">' . $bobot[$k->id_kriteria] . '</td>';
        }
        $data_bobot .= '</tr>';
        $data_bobot .= '</tbody>';

        //table perangkingan
        $data_perangkingan = '<thead class="text-center align-middle">
        <tr>
            <th rowspan="2" style="text-align:center">#</th>
            <th rowspan="2" style="text-align:center">Kode Alternative</th>
            <th class="text-center" colspan="' . count($getKriteria) . '">Kriteria</th>
            <th rowspan="2" style="text-align:center">Hasil</th>
        </tr>
        <tr>';
        $data_perangkingan .= $kode_kriteria;
        $data_perangkingan .= '</tr></thead>';
        $data_perangkingan .= '<tbody>';
        $no_perangkingan = 1;
        foreach ($getAlternative->getResult() as $a) {
            $data_perangkingan .= '<tr><td class="text-center">' . $no_perangkingan++ . '</td>
                                <td>' . $a->kode_alternative . '</td>';

            foreach ($nilai_bobot[$a->id_alternative] as $b) {
                $data_perangkingan .= '<td class="text-center">' . $b . '</td>';
            }

            $data_perangkingan .= '<td class="text-center">' . $hasil_arr[$a->id_alternative] . '</td>';
            $data_perangkingan .= '</tr>';
        }

        $get_data = $model->getWinner();

        $data = [
            'title'             => 'Cetak Hasil Perhitungan',
            'data_analisa1'     => $data_analisa1,
            'data_analisa2'     => $data_analisa2,
            'data_normalisasi'  => $data_normalisasi,
            'data_bobot'        => $data_bobot,
            'data_perangkingan' => $data_perangkingan,
            'get'               => $get_data
        ];

        $html = view('cetak', $data);

        $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Cetak Hasil Perhitungan');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->addPage();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('Hasil-perhitungan.pdf', 'I');
    }

    private function normalisasi($id_kriteria = null, $nilai = null)
    {
        //load model penilaian
        $model = new PenilaianModel();
        //ambil data penilaian
        $get = $model->getNilaiPenilaian($id_kriteria)->getRowArray();

        if ($get['sifat'] === 'benefit') {
            $result = round(($nilai / $get['nilai_max']), 3);
        } else {
            $result = round(($get['nilai_min'] / $nilai), 3);
        }

        return $result;
    }
}
