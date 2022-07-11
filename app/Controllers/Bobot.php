<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BobotModel;
use App\Models\KriteriaModel;
use App\Models\SubKriteriaModel;

class Bobot extends BaseController
{
    function __construct()
    {
        if (session()->get('role') != 'admin') {
            echo view('access_denied_message');
            exit;
        }
    }

    public function index()
    {
        //load model kriteria
        $kriteria = new KriteriaModel();
        //load model sub kriteria
        $sub_kriteria = new SubKriteriaModel();
        //ambil data kriteria yang memiliki sub kategori
        $getKriteria = $kriteria->getFormKriteria();
        $form = '';
        foreach ($getKriteria->getResult() as $k) {
            $form .= '<input type="hidden" name="idKriteria[]" value="' . $k->id_kriteria . '" />';
            $form .= '<div class="form-group row mb-3">';
            $form .= '<label class="col-sm-12 col-md-3 col-form-label right-label">' . $k->judul_kriteria . '</label>';
            $form .= '<div class="col-sm-12 col-md-5">';
            $form .= '<select name="kriteria[]" class="form-control" required>';
            $form .= '<option value="" selected>Pilih ' . $k->judul_kriteria . '</option>';
            //ambil data sub kriteria sesuai id kriteria
            $getSub = $sub_kriteria->getSubkriteria($k->id_kriteria);
            foreach ($getSub->getResult() as $s) {
                $id_pilih = ($this->session->getFlashdata('kriteria_' . $k->id_kriteria)) ? $this->session->getFlashdata('kriteria_' . $k->id_kriteria) : $s->id_sub;
                $pilih = ($id_pilih == $s->id_sub_kriteria) ? 'selected' : '';

                $form .= '<option value="' . $s->id_sub_kriteria . '" ' . $pilih . '>' . $s->keterangan . '</option>';
            }

            $form .= '</select>';
            $form .= '</div>';
            $form .= '</div>';
        }

        $data = [
            'title' => 'Bobot',
            'form'  => $form
        ];

        return view('bobot', $data);
    }

    public function simpan()
    {
        if ($this->request->getPost('Submit') == 'Submit') {
            //tampung data yang dikirim
            $id_kriteria = $this->request->getPost('idKriteria');
            $id_sub_kriteria = $this->request->getPost('kriteria');

            //validasi
            if (!$this->validate([
                'idKriteria.*' => 'required|is_not_unique[tbl_kriteria.id_kriteria]',
                'kriteria.*' => 'required|is_not_unique[tbl_sub_kriteria.id_sub_kriteria]'
            ])) {
                $this->session->setFlashdata('failed', 'Data yang dimasukkan tidak valid');

                return redirect()->back();
            }
            //load model bobot
            $model = new BobotModel();
            //kosongkan tabel bobot
            $model->deleteAll();
            //masukkan data ke array sebelum disimpan
            $arr_data = array();

            for ($i = 0; $i < count($id_sub_kriteria); $i++) {
                $data = [
                    'id_kriteria' => $id_kriteria[$i],
                    'id_sub_kriteria' => $id_sub_kriteria[$i]
                ];

                //push array
                array_push($arr_data, $data);
            }

            //simpan bobot
            $simpan = $model->multiSave($arr_data);

            if ($simpan) {
                $this->session->setFlashdata('success', 'Bobot berhasil disimpan');
            } else {
                $this->session->setFlashdata('failed', 'Bobot gagal disimpan');
            }

            return redirect()->back();
        }

        return redirect()->back();
    }
}
