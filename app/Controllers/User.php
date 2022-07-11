<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
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
            'title' => 'Manajemen User'
        ];

        return view('user', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah User'
        ];

        return view('add_user', $data);
    }

    public function simpan()
    {
        if ($this->request->getPost('Submit') == 'Submit') {
            //validasi data
            if (!$this->validate([
                'user' => [
                    'label'     => 'Username',
                    'rules'     => 'required|is_unique[tbl_user.username]|min_length[5]|max_length[35]',
                    'errors'    => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} sudah digunakan, silahkan pilih yang lain',
                        'min_length'    => '{field} minimal 5 karakter',
                        'max_length'    => '{field} maksimal 35 karakter'
                    ]
                ],
                'nama' => [
                    'label'     => 'Fullname',
                    'rules'     => 'required|min_length[3]|max_length[50]',
                    'errors'    => [
                        'required'      => '{field} wajib diisi',
                        'min_length'    => '{field} minimal 3 karakter',
                        'max_length'    => '{field} maksimal 50 karakter'
                    ]
                ],
                'password' => [
                    'label'     => 'Password',
                    'rules'     => 'required',
                    'errors'    => [
                        'required'  => '{field} wajib diisi'
                    ]
                ],
                'role'  => [
                    'label'     => 'Role',
                    'rules'     => 'required|in_list[admin,user]',
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

            $data = [
                'username'  => $this->request->getPost('user'),
                'fullname'  => $this->request->getPost('nama'),
                'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT, ['cost' => 8]),
                'role'      => $this->request->getPost('role')
            ];

            //load model user
            $model = new UserModel();

            $simpan = $model->save($data);

            if ($simpan) {
                $this->session->setFlashdata('success', 'User berhasil ditambahkan');
            } else {
                $this->session->setFlashdata('failed', 'User gagal ditambahkan');
            }

            return redirect()->to('user');
        }

        return redirect()->back();
    }

    public function edit($id = null)
    {
        //decrypt dan cek id
        $id_user = decrypt_url($id);

        if ($id_user == null || $id_user == '') {
            return redirect()->back();
        }

        //load model user
        $model = new UserModel();
        //ambil data
        $get = $model->find($id_user);

        //cek data
        if (!$get) {
            return redirect()->back();
        }

        $data = [
            'title' => 'Edit User',
            'data'  => $get
        ];

        return view('edit_user', $data);
    }

    public function update($id = null)
    {
        //decrypt dan cek id
        $id_user = decrypt_url($id);

        if ($id_user == null || $id_user == '') {
            return redirect()->back();
        }

        //load model user
        $model = new UserModel();
        //ambil data
        $get = $model->find($id_user);

        if (!$get) {
            //masukkan pesan kesalahan ke flashdata
            $this->session->setFlashdata('failed', 'User tidak terdaftar');
            //redirect ke halaman manajemen user
            return redirect()->to('/user');
        }


        if ($this->request->getPost('Submit') == 'Submit') {
            //validasi data
            if (!$this->validate([
                'user' => [
                    'label'     => 'Username',
                    'rules'     => 'required|is_unique[tbl_user.username,username,' . $get['username'] . ']|min_length[5]|max_length[35]',
                    'errors'    => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} sudah digunakan, silahkan pilih yang lain',
                        'min_length'    => '{field} minimal 5 karakter',
                        'max_length'    => '{field} maksimal 35 karakter'
                    ]
                ],
                'nama' => [
                    'label'     => 'Fullname',
                    'rules'     => 'required|min_length[3]|max_length[50]',
                    'errors'    => [
                        'required'      => '{field} wajib diisi',
                        'min_length'    => '{field} minimal 3 karakter',
                        'max_length'    => '{field} maksimal 50 karakter'
                    ]
                ],
                'role'  => [
                    'label'     => 'Role',
                    'rules'     => 'required|in_list[admin,user]',
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

            $password = $get['password'];
            if ($this->request->getPost('password')) {
                $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT, ['cost' => 8]);
            }

            $data = [
                'id_user'   => $get['id_user'],
                'username'  => $this->request->getPost('user'),
                'fullname'  => $this->request->getPost('nama'),
                'password'  => $password,
                'role'      => $this->request->getPost('role')
            ];

            $update = $model->save($data);

            if ($update) {
                $this->session->setFlashdata('success', 'Data User berhasil diperbarui');
            } else {
                $this->session->setFlashdata('failed', 'Data User gagal diperbarui');
            }

            return redirect()->to('user');
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
                $model = new UserModel();
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
        //load model User
        $model = new UserModel();

        if ($this->request->isAJAX()) {
            //ambil data
            $lists = $model->get_datatables();

            $data = [];
            $no = $this->request->getPost("start");

            foreach ($lists as $list) {
                $button = '
                        <a href="' . base_url('user/' . encrypt_url($list->id_user)) . '" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-xs btn-danger my-1" onclick="sweetDelete(\'' . encrypt_url($list->id_user) . '\')">
                            <i class="fas fa-trash"></i>
                        </button>';
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->username;
                $row[] = $list->fullname;
                $row[] = $list->role;
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
