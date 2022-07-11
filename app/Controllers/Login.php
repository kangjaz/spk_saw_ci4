<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        //load view login form
        return view('login');
    }

    public function proses_login()
    {
        if ($this->request->isAJAX()) {
            //lakukan validasi data
            if (!$this->validate([
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required'
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required'
                ]
            ])) {
                $data = [
                    'status' => 'failed',
                    'message' => 'Data yang dimasukkan tidak valid'
                ];
            } else {
                //load model user
                $user = new UserModel();
                //ambil data sesuai
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');

                $get = $user->where('username', $username)->first();

                if ($get) {
                    //validasi password
                    if (password_verify($password, $get['password'])) {
                        //masukkan data ke session
                        $this->session->set([
                            'idUser'    => encrypt_url($get['id_user']),
                            'user'      => $get['fullname'],
                            'role'      => $get['role'],
                            'isLogin'   => true
                        ]);

                        $data = [
                            'status' => 'success',
                            'message' => 'Login Berhasil'
                        ];
                    } else {
                        $data = [
                            'status' => 'failed',
                            'message' => 'Password yang anda masukkan salah'
                        ];
                    }
                } else {
                    $data = [
                        'status' => 'failed',
                        'message' => 'Username tidak dikenali'
                    ];
                }
            }
            $data['token'] = csrf_hash();

            echo json_encode($data);
        }
    }
}
