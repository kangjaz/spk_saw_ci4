<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlternativeModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\SubKriteriaModel;

class Penilaian extends BaseController
{
    public function index()
    {
        //load model alternative
        $alternative = new AlternativeModel();
        //load model kriteria
        $kriteria = new KriteriaModel();
        //load model sub kriteria
        $sub_kriteria = new SubKriteriaModel();
        //ambil data kriteria yang memiliki sub kategori
        $getKriteria = $kriteria->getFormKriteria();
        $form = '';
        foreach ($getKriteria->getResult() as $k) {
            $form .= '<input type="hidden" name="idKriteria[]" value="' . $k->id_kriteria . '" />';
            $form .= '<div class="form-group mb-2">';
            $form .= '<label>' . $k->judul_kriteria . '</label>';
            $form .= '<select name="kriteria[]" class="form-control form-control-sm">';
            $form .= '<option value="" selected>Pilih ' . $k->judul_kriteria . '</option>';
            //ambil data sub kriteria sesuai id kriteria
            $getSub = $sub_kriteria->getSubkriteriaPenilaian($k->id_kriteria);
            foreach ($getSub->getResult() as $s) {
                $pilih = ($s->id_sub_kriteria == $this->session->getFlashdata('kriteria_' . $k->id_kriteria)) ? 'selected' : '';

                $form .= '<option value="' . $s->id_sub_kriteria . '" ' . $pilih . '>' . $s->keterangan . '</option>';
            }

            $form .= '</select>';
            $form .= '</div>';
        }

        //load model penilaian
        $model = new PenilaianModel();
        //ambil data penilaian
        $getPenilaian = $model->getPenilaian();

        $data = [
            'title'         => 'Penilaian',
            'form'          => $form,
            'alternative'    => $alternative->findAll(),
            'penilaian'     => $getPenilaian
        ];

        return view('penilaian', $data);
    }

    public function simpan()
    {
        if ($this->request->getPost('Submit')) {
            //tampung semua value yang dikirim
            $id_alternative = $this->request->getPost('alternative');
            $id_kriteria = $this->request->getPost('idKriteria');
            $id_sub_kriteria = $this->request->getPost('kriteria');

            if (!$this->validate([
                'alternative' => 'required|is_not_unique[tbl_alternative.id_alternative]',
                'idKriteria.*' => 'required|is_not_unique[tbl_kriteria.id_kriteria]',
                'kriteria.*' => 'required|is_not_unique[tbl_sub_kriteria.id_sub_kriteria]'
            ])) {
                $arr_notif = array();

                for ($i = 0; $i < count($id_sub_kriteria); $i++) {
                    $arr_notif['kriteria_' . $id_kriteria[$i]] = $id_sub_kriteria[$i];
                }

                $arr_notif['failed'] = 'Data tidak valid';
                $this->session->setFlashdata($arr_notif);

                return redirect()->back()->withInput();
            }

            $arr_data = array();

            for ($i = 0; $i < count($id_sub_kriteria); $i++) {
                $data = [
                    'id_alternative' => $id_alternative,
                    'id_kriteria' => $id_kriteria[$i],
                    'id_sub_kriteria' => $id_sub_kriteria[$i]
                ];
                //push array
                array_push($arr_data, $data);
            }

            //cek apakah terdapat alternative yang sama di database
            $model = new PenilaianModel();

            $cekAlternative = $model->cekAlternative($id_alternative);

            if ($cekAlternative > 0) {
                $this->session->setFlashdata('failed', 'Data sudah ada dalam penilaian');

                return redirect()->back();
            }

            //simpan penilaian
            $simpan = $model->multiSave($arr_data);

            if ($simpan) {
                $this->session->setFlashdata('success', 'Penilaian berhasil disimpan');
            } else {
                $this->session->setFlashdata('failed', 'Penilaian gagal disimpan');
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getDetail()
    {
        if ($this->request->isAJAX()) {
            //decrype data id yang dikirim
            $id = decrypt_url($this->request->getPost('id'));

            if ($id == null || $id == '') {
                $data = [
                    'status' => 'failed'
                ];
            } else {
                //load model penilaian
                $alternative = new PenilaianModel();
                //ambil data
                $getData = $alternative->getPenilaianAlternative($id);

                if (count($getData) < 1) {
                    $data = [
                        'status' => 'failed'
                    ];
                } else {
                    $body = '';
                    $header = '';
                    foreach ($getData as $k) {
                        $header = $k['nama_alternative'];

                        $body .= '<div class="row py-2">
                                    <div class="col-sm-4 col-xs-11 right-label fw-bold">' . $k['judul_kriteria'] . ' :</div>
                                    <div class="col-sm-7 col-xs-11">' . $k['keterangan'] . '</div>
                                    </div>';
                    }

                    $data = [
                        'status'    => 'success',
                        'header'    => 'Detail Penilaian ' . $header,
                        'body'      => $body
                    ];
                }
            }
            $data['token'] = csrf_hash();

            echo json_encode($data);
        } else {
            return redirect()->back();
        }
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            //tampung dan decrypt id
            $id = decrypt_url($this->request->getPost('id'));

            if ($id == null || $id == '') {
                $data = [
                    'status' => 'failde'
                ];
            } else {
                //load model alternative
                $alternative = new AlternativeModel();
                //load model kriteria
                $kriteria = new KriteriaModel();
                //load model sub kriteria
                $sub_kriteria = new SubKriteriaModel();
                //ambil data kriteria yang memiliki sub kategori
                $getKriteria = $kriteria->getFormKriteria();
                //ambil data alternative sesuai dengan id
                $getAlternative = $alternative->find($id);

                if ($getAlternative) {
                    $form = '<div class="form-group mb-2">';
                    $form .= '<label>Alternative</label>';
                    $form .= '<select name="alternative" class="form-control form-control-sm" disabled>';
                    $form .= '<option value="" selected>' . $getAlternative['nama_alternative'] . '</option>';
                    $form .= '</select>';
                    $form .= '</div>';
                    foreach ($getKriteria->getResult() as $k) {
                        $form .= '<input type="hidden" name="idKriteria[]" value="' . $k->id_kriteria . '" />';
                        $form .= '<div class="form-group mb-2">';
                        $form .= '<label>' . $k->judul_kriteria . '</label>';
                        $form .= '<select name="kriteria[]" class="form-control form-control-sm">';
                        $form .= '<option value="" selected>Pilih ' . $k->judul_kriteria . '</option>';
                        //ambil data sub kriteria sesuai id kriteria
                        $getSub = $sub_kriteria->getSubkriteriaAlternative($k->id_kriteria, $id);
                        foreach ($getSub->getResult() as $s) {
                            $id_pilih = ($this->session->getFlashdata('kriteria_' . $k->id_kriteria)) ? $this->session->getFlashdata('kriteria_' . $k->id_kriteria) : $s->id_sub;

                            $pilih = ($s->id_sub_kriteria == $id_pilih) ? 'selected' : '';

                            $form .= '<option value="' . $s->id_sub_kriteria . '" ' . $pilih . '>' . $s->keterangan . '</option>';
                        }

                        $form .= '</select>';
                        $form .= '</div>';
                    }

                    $data = [
                        'status'        => 'success',
                        'header'        => 'Update Data',
                        'body'          => $form,
                        'url_action'    => base_url('penilaian/' . encrypt_url($id))
                    ];
                } else {
                    $data = [
                        'status' => 'failed'
                    ];
                }
            }

            $data['token'] = csrf_hash();

            echo json_encode($data);
        } else {
            return redirect()->back();
        }
    }

    public function update($id = null)
    {
        if ($this->request->getPost('Submit') == 'Submit') {
            //decrypt id dan cek variabel id_alternative
            $id_alternative = decrypt_url($id);

            if ($id_alternative == null || $id_alternative == '') {
                return redirect()->back();
            }
            //cek data alternative di database
            $alternative = new AlternativeModel();
            $getAlternative = $alternative->find($id_alternative);

            if (!$getAlternative) {
                $this->session->setFlashdata('failed', 'Alternative tidak dikenali');
                return redirect()->back();
            }

            //tampung value input ke variabel
            $id_kriteria = $this->request->getPost('idKriteria');
            $id_sub_kriteria = $this->request->getPost('kriteria');

            //lakukan validasi input
            if (!$this->validate([
                'idKriteria.*' => 'required|is_not_unique[tbl_kriteria.id_kriteria]',
                'kriteria.*' => 'required|is_not_unique[tbl_sub_kriteria.id_sub_kriteria]'
            ])) {
                $arr_notif = array();

                for ($i = 0; $i < count($id_sub_kriteria); $i++) {
                    $arr_notif['kriteria_' . $id_kriteria[$i]] = $id_sub_kriteria[$i];
                }

                $form = '<div class="form-group mb-2">';
                $form .= '<label>Alternative</label>';
                $form .= '<select name="alternative" class="form-control form-control-sm" disabled>';
                $form .= '<option value="" selected>' . $getAlternative['nama_alternative'] . '</option>';
                $form .= '</select>';
                $form .= '</div>';

                $arr_notif['failed'] = 'Data tidak valid';
                $arr_notif['alternative'] = $form;
                $arr_notif['url_update'] = base_url('penilaian/' . encrypt_url($getAlternative['id_alternative']));

                $this->session->setFlashdata($arr_notif);

                return redirect()->back()->withInput();
            }

            $arr_data = array();

            for ($i = 0; $i < count($id_sub_kriteria); $i++) {
                $data = [
                    'id_alternative' => $id_alternative,
                    'id_kriteria' => $id_kriteria[$i],
                    'id_sub_kriteria' => $id_sub_kriteria[$i]
                ];
                //push array
                array_push($arr_data, $data);
            }

            //load model penilaian
            $model = new PenilaianModel();
            //hapus data penilaian lama sesuai id alternative
            $model->deletePenilaian($id_alternative);
            //simpan penilaian
            $simpan = $model->multiSave($arr_data);

            if ($simpan) {
                $this->session->setFlashdata('success', 'Penilaian berhasil diperbarui');
            } else {
                $this->session->setFlashdata('failed', 'Penilaian gagal diperbarui');
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            //decrypt id dan cek variabel id_alternative
            $id_alternative = decrypt_url($this->request->getPost('id'));

            if ($id_alternative == null || $id_alternative == '') {
                $data = [
                    'status' => 'failed'
                ];
            } else {
                //cek data alternative di database
                $alternative = new AlternativeModel();
                $getAlternative = $alternative->find($id_alternative);

                if (!$getAlternative) {
                    $data = [
                        'status' => 'failed'
                    ];
                } else {
                    //load model penilaian
                    $model = new PenilaianModel();
                    //hapus data penilaian lama sesuai id alternative
                    $model->deletePenilaian($id_alternative);
                    $data = [
                        'status' => 'success'
                    ];
                }
            }

            echo json_encode($data);
        } else {
            return redirect()->back();
        }
    }
}
