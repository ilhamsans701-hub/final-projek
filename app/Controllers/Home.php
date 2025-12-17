<?php

class Home extends Controller {
    public function index()
    {
        // 1. CEK SESI
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 'orangtua') {
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            } else {
                header('Location: ' . BASEURL . '/student');
                exit;
            }
        }

        // 2. JIKA TAMU
        $data['judul'] = 'MyMoney - Kelola Keuangan Keluarga';
        
        $this->view('home/index', $data);               
    }
}