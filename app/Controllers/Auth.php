<?php

class Auth extends Controller {
    public function index()
    {
        $data['judul'] = 'Login MyMoney';
        $this->view('templates/header', $data);
        $this->view('auth/login');
        $this->view('templates/footer');
    }

    public function register()
    {
        $data['judul'] = 'Register MyMoney';
        $this->view('templates/header', $data);
        $this->view('auth/register');
        $this->view('templates/footer');
    }

    public function processLogin()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        $username = $_POST['username'];
        $password = $_POST['password'];

        $userModel = $this->model('User_model');
        $user = $userModel->getUserByUsername($username);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Set Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['parent_id'] = $user['parent_id'];

                // Redirect berdasarkan Role
                if ($user['role'] == 'orangtua') {
                    header('Location: ' . BASEURL . '/dashboard');
                } else {
                    header('Location: ' . BASEURL . '/student'); 
                }
                exit;
            } else {
                Flasher::setFlash('gagal', 'Password salah', 'danger');
                header('Location: ' . BASEURL . '/auth');
                exit;
            }
        } else {
            Flasher::setFlash('gagal', 'Username tidak ditemukan', 'danger');
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function processRegister()
    {
        if( $this->model('User_model')->registerUser($_POST) > 0 ) {
            Flasher::setFlash('berhasil', 'Akun berhasil dibuat, silakan login', 'success');
            header('Location: ' . BASEURL . '/auth');
            exit;
        } else {
            Flasher::setFlash('gagal', 'Registrasi gagal. Cek Kode Keluarga (jika Anak) atau Username mungkin sudah dipakai.', 'danger');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
    }

    public function logout()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }
}