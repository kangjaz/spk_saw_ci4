<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KriteriaModel;
use App\Models\SubKriteriaModel;

class SubKriteria extends BaseController
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

        $data = [
            'title'     => 'Sub Kriteria',
            'kriteria'  => $kriteria->findAll()
        ];

        return view('sub_kriteria', $data);
    }

    public function simpan()
    {
        if ($this->request->getPost('Submit')) {
            //lakukan validasi data
            if (!$this->validate([
                'kriteria' => [
                    'label'     => 'Kriteria',
                    'rules'     => 'required|is_not_unique[tbl_kriteria.id_kriteria]',
                    'errors'    => [
                        'required' => '{field} wajib dipilih',
                        'is_not_unique' => '{field} tidak terdaftar'
                    ]
                ],
                'nilai' => [
                    'label'     => 'Nilai',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required' => '{field} wajib diisi',
                        'decimal' => '{field} tidak valid'
                    ]
                ],
                'keterangan' => [
                    'label'     => 'Keterangan',
                    'rules'     => 'required|max_length[255]',
                    'errors'    => [
                        'required' => '{field} wajib diisi',
                        'max_length' => '{field} maksimal 255 karakter'
                    ]
                ]
            ])) {
                $validation = \Config\Services::validation();
                //masukkan pesan kesalahan ke flashdata
                $this->session->setFlashdata('validation', $validation->getErrors());
                //redirect ke halaman sebelumnya beserta mengirimkan isian input
                return redirect()->back()->withInput();
            }

            //simpan data
            $data = [
                'id_kriteria'   => $this->request->getPost('kriteria'),
                'nilai'         => $this->request->getPost('nilai'),
                'keterangan'    => $this->request->getPost('keterangan')
            ];

            //load model sub kriteria
            $model = new SubKriteriaModel();

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
                $model = new SubKriteriaModel();
                //ambil data
                $get = $model->find($id);
                //cek data
                if ($get) {
                    $data = [
                        'status'        => 'success',
                        'header'        => 'Update Data',
                        'kriteria'      => $get['id_kriteria'],
                        'nilai'         => $get['nilai'],
                        'keterangan'    => $get['keterangan'],
                        'url_action'    => base_url('sub-kriteria/' . encrypt_url($get['id_sub_kriteria']))
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
            $id_sub_kriteria = decrypt_url($id);

            if ($id_sub_kriteria == '' || $id_sub_kriteria == null) {
                return redirect()->back();
            }

            //load model
            $model = new SubKriteriaModel();
            //ambild dan cek data sub kriteria sesuai id
            $get = $model->find($id_sub_kriteria);

            if (!$get) {
                return redirect()->back();
            }
            //lakukan validasi
            if (!$this->validate([
                'kriteria' => [
                    'label'     => 'Kriteria',
                    'rules'     => 'required|is_not_unique[tbl_kriteria.id_kriteria]',
                    'errors'    => [
                        'required' => '{field} wajib dipilih',
                        'is_not_unique' => '{field} tidak terdaftar'
                    ]
                ],
                'nilai' => [
                    'label'     => 'Nilai',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required' => '{field} wajib diisi',
                        'decimal' => '{field} tidak valid'
                    ]
                ],
                'keterangan' => [
                    'label'     => 'Keterangan',
                    'rules'     => 'required|max_length[255]',
                    'errors'    => [
                        'required' => '{field} wajib diisi',
                        'max_length' => '{field} maksimal 255 karakter'
                    ]
                ]
            ])) {
                $validation = \Config\Services::validation();
                //masukkan pesan kesalahan ke flashdata
                $this->session->setFlashdata([
                    'validation' => $validation->getErrors(),
                    'url_update' => base_url('sub-kriteria/' . $id)
                ]);
                //redirect ke halaman sebelumnya beserta mengirimkan isian input
                return redirect()->back()->withInput();
            }

            //update data
            $data = [
                'id_sub_kriteria'   => $id_sub_kriteria,
                'id_kriteria'       => $this->request->getPost('kriteria'),
                'nilai'             => $this->request->getPost('nilai'),
                'keterangan'        => $this->request->getPost('keterangan')
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
                $model = new SubKriteriaModel();
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
        //load model sub kriteria
        $model = new SubKriteriaModel();

        if ($this->request->isAJAX()) {
            //ambil data
            $lists = $model->get_datatables();

            $data = [];
            $no = $this->request->getPost("start");

            foreach ($lists as $list) {
                $button = '
				<button type="button" class="btn btn-xs btn-warning my-1" onclick="getData(\'' . encrypt_url($list->id_sub_kriteria) . '\')">
					<i class="fas fa-edit"></i>
				</button>
				<button type="button" class="btn btn-xs btn-danger my-1" onclick="sweetDelete(\'' . encrypt_url($list->id_sub_kriteria) . '\')">
					<i class="fas fa-trash"></i>
				</button>';
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->judul_kriteria;
                $row[] = $list->nilai;
                $row[] = $list->keterangan;
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
