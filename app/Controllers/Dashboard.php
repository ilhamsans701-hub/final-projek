<?php

class Dashboard extends Controller {
    public function index()
    {
        // Cek Sesi: Harus Login dan Harus Orang Tua
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'orangtua') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $data['judul'] = 'Dashboard Orang Tua';
        $data['user'] = $_SESSION['username'];
        
        // Nanti kita tambahkan logika mengambil data anak disini
        // Untuk sekarang tampilan dasar dulu
        
        $this->view('templates/header', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer');
    }
}