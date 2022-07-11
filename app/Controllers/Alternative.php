<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlternativeModel;

class Alternative extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Data Alternative'
        ];

        return view('alternative', $data);
    }

    public function simpan()
    {
        if ($this->request->getPost('Submit') == 'Submit') {
            //lakukan validasi
            if (!$this->validate([
                'kode' => [
                    'label' => 'Kode Alternative',
                    'rules' => 'required|max_length[5]|is_unique[tbl_alternative.kode_alternative]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 5 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ],
                'nama' => [
                    'label' => 'Nama Alternative',
                    'rules' => 'required|max_length[255]|is_unique[tbl_alternative.nama_alternative]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 255 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ]
            ])) {
                $validation = \Config\Services::validation();
                //masukkan pesan kesalahan ke flashdata
                $this->session->setFlashdata('validation', $validation->getErrors());
                //redirect ke halaman sebelumnya beserta mengirimkan isian input
                return redirect()->back()->withInput();
            }

            //load model alternative
            $model = new AlternativeModel();
            //simpan data
            $data = [
                'kode_alternative' => $this->request->getPost('kode'),
                'nama_alternative' => $this->request->getPost('nama')
            ];

            $simpan = $model->save($data);

            if ($simpan) {
                $this->session->setFlashdata('success', 'Data berhasil disimpan');
            } else {
                $this->session->setFlashdata('failed', 'Data gagal disimpan');
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getData()
    {
        //cek apakah request berupa ajax atau bukan
        if ($this->request->isAJAX()) {
            //tampung data dan decrypt
            $id = decrypt_url($this->request->getPost('id'));
            //cek data
            if ($id == null || $id == '') {
                $data = [
                    'status' => 'failed'
                ];
            } else {
                //load model
                $model = new AlternativeModel();
                //ambil data
                $get = $model->find($id);
                //cek data
                if ($get) {
                    $data = [
                        'status'        => 'success',
                        'header'        => 'Update Data',
                        'kode'          => $get['kode_alternative'],
                        'nama'          => $get['nama_alternative'],
                        'url_action'    => base_url('alternative/' . encrypt_url($get['id_alternative']))
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
            //lakukan decrypt id dan cek variabel id
            $id_alternative = decrypt_url($id);

            if ($id_alternative == '' || $id_alternative == null) {
                return redirect()->back();
            }
            //load model
            $model = new AlternativeModel();
            //ambild dan cek data alternative sesuai id
            $get = $model->find($id_alternative);

            if (!$get) {
                return redirect()->back();
            }

            //validasi input
            if (!$this->validate([
                'kode' => [
                    'label' => 'Kode Alternative',
                    'rules' => 'required|max_length[5]|is_unique[tbl_alternative.kode_alternative,kode_alternative,' . $get['kode_alternative'] . ']',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 5 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ],
                'nama' => [
                    'label'     => 'Nama Alternative',
                    'rules'     => 'required|max_length[255]|is_unique[tbl_alternative.nama_alternative,nama_alternative,' . $get['nama_alternative'] . ']',
                    'errors'    => [
                        'required'  => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 255 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ]
            ])) {
                $validation = \Config\Services::validation();
                //masukkan pesan kesalahan ke flashdata
                $this->session->setFlashdata([
                    'validation' => $validation->getErrors(),
                    'url_update' => base_url('alternative/' . $id)
                ]);
                //redirect ke halaman sebelumnya beserta mengirimkan isian input
                return redirect()->back()->withInput();
            }

            //update data
            $data = [
                'id_alternative'     => $id_alternative,
                'kode_alternative'   => $this->request->getPost('kode'),
                'nama_alternative'   => $this->request->getPost('nama')
            ];

            $update = $model->save($data);

            if ($update) {
                $this->session->setFlashdata('success', 'Data berhasil diperbarui');
            } else {
                $this->session->setFlashdata('failed', 'Data gagal diperbarui');
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = decrypt_url($this->request->getPost('id'));
            //cek id
            if ($id == null || $id == '') {
                $data = [
                    'status' => 'failed'
                ];
            } else {
                //load model
                $model = new AlternativeModel();
                //hapus data
                $delete = $model->delete($id);

                if ($delete) {
                    $data = [
                        'status' => 'success'
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

    public function ajaxList()
    {
        //load model alternative
        $model = new AlternativeModel();

        if ($this->request->isAJAX()) {
            //ambil data
            $lists = $model->get_datatables();

            $data = [];
            $no = $this->request->getPost("start");

            foreach ($lists as $list) {
                $button = '
				<button type="button" class="btn btn-xs btn-warning my-1" onclick="getData(\'' . encrypt_url($list->id_alternative) . '\')">
					<i class="fas fa-edit"></i>
				</button>
				<button type="button" class="btn btn-xs btn-danger my-1" onclick="sweetDelete(\'' . encrypt_url($list->id_alternative) . '\')">
					<i class="fas fa-trash"></i>
				</button>';
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->kode_alternative;
                $row[] = $list->nama_alternative;
                $row[] = $button;
                $data[] = $row;
            }

            $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $model->count_all(),
                "recordsFiltered" => $model->count_filtered(),
                "data" => $data
            ];

            $output[csrf_token()] = csrf_hash();
            echo json_encode($output);
        }
    }
}
