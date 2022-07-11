<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KriteriaModel;

class Kriteria extends BaseController
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
        $data = [
            'title' => 'Kriteria'
        ];

        return view('kriteria', $data);
    }

    public function simpan()
    {
        if ($this->request->getPost('Submit') == 'Submit') {
            //lakukan validasi
            if (!$this->validate([
                'kode' => [
                    'label' => 'Kode Kriteria',
                    'rules' => 'required|max_length[5]|is_unique[tbl_kriteria.kode_kriteria]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 5 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ],
                'judul' => [
                    'label' => 'Judul Kriteria',
                    'rules' => 'required|max_length[255]|is_unique[tbl_kriteria.judul_kriteria]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 255 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ],
                'sifat' => [
                    'label'     => 'Sifat',
                    'rules'     => 'required|in_list[cost,benefit]',
                    'errors'    => [
                        'required'  => '{field} wajib dipilih',
                        'in_list'   => '{field} tidak valid'
                    ]
                ]
            ])) {
                $validation = \Config\Services::validation();
                //masukkan pesan kesalahan ke flashdata
                $this->session->setFlashdata('validation', $validation->getErrors());
                //redirect ke halaman sebelumnya beserta mengirimkan isian input
                return redirect()->back()->withInput();
            }

            //load model kriteria
            $model = new KriteriaModel();
            //simpan data
            $data = [
                'kode_kriteria'     => $this->request->getPost('kode'),
                'judul_kriteria'    => $this->request->getPost('judul'),
                'sifat'             => $this->request->getPost('sifat')
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
                $model = new KriteriaModel();
                //ambil data
                $get = $model->find($id);
                //cek data
                if ($get) {
                    $data = [
                        'status'        => 'success',
                        'header'        => 'Update Data',
                        'kode'          => $get['kode_kriteria'],
                        'judul'         => $get['judul_kriteria'],
                        'sifat'         => $get['sifat'],
                        'url_action'    => base_url('kriteria/' . encrypt_url($get['id_kriteria']))
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
            $id_kriteria = decrypt_url($id);

            if ($id_kriteria == '' || $id_kriteria == null) {
                return redirect()->back();
            }

            //load model
            $model = new KriteriaModel();
            //ambild dan cek data kriteria sesuai id
            $get = $model->find($id_kriteria);

            if (!$get) {
                return redirect()->back();
            }
            //lakukan validasi
            if (!$this->validate([
                'kode' => [
                    'label' => 'Kode Kriteria',
                    'rules' => 'required|max_length[5]|is_unique[tbl_kriteria.kode_kriteria,kode_kriteria,' . $get['kode_kriteria'] . ']',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 5 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ],
                'judul' => [
                    'label' => 'Judul Kriteria',
                    'rules' => 'required|max_length[255]|is_unique[tbl_kriteria.judul_kriteria,judul_kriteria,' . $get['judul_kriteria'] . ']',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'max_length'    => '{field} maksimal 255 karakter',
                        'is_unique'     => '{field} sudah digunakan, silahkan ganti'
                    ]
                ],
                'sifat' => [
                    'label'     => 'Sifat',
                    'rules'     => 'required|in_list[cost,benefit]',
                    'errors'    => [
                        'required'  => '{field} wajib dipilih',
                        'in_list'   => '{field} tidak valid'
                    ]
                ]
            ])) {
                $validation = \Config\Services::validation();
                //masukkan pesan kesalahan ke flashdata
                $this->session->setFlashdata([
                    'validation' => $validation->getErrors(),
                    'url_update' => base_url('kriteria/' . $id)
                ]);
                //redirect ke halaman sebelumnya beserta mengirimkan isian input
                return redirect()->back()->withInput();
            }

            //update data
            $data = [
                'id_kriteria'       => $id_kriteria,
                'kode_kriteria'     => $this->request->getPost('kode'),
                'judul_kriteria'    => $this->request->getPost('judul'),
                'sifat'             => $this->request->getPost('sifat')
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
                $model = new KriteriaModel();
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
        //load model kriteria
        $model = new KriteriaModel();

        if ($this->request->isAJAX()) {
            //ambil data
            $lists = $model->get_datatables();

            $data = [];
            $no = $this->request->getPost("start");

            foreach ($lists as $list) {
                $button = '
				<button type="button" class="btn btn-xs btn-warning my-1" onclick="getData(\'' . encrypt_url($list->id_kriteria) . '\')">
					<i class="fas fa-edit"></i>
				</button>
				<button type="button" class="btn btn-xs btn-danger my-1" onclick="sweetDelete(\'' . encrypt_url($list->id_kriteria) . '\')">
					<i class="fas fa-trash"></i>
				</button>';
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->kode_kriteria;
                $row[] = $list->judul_kriteria;
                $row[] = ucwords($list->sifat);
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
