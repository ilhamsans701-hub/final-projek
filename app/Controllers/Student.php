<?php

class Student extends Controller {
    public function index()
    {
        // Cek Sesi: Harus Login dan Harus Anak
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Panggil Model
        $transModel = $this->model('Transaction_model');
        
        $data['judul'] = 'Dashboard Mahasiswa';
        $data['user'] = $_SESSION['username'];
        $data['summary'] = $transModel->getSummary($userId);
        $data['transactions'] = $transModel->getAllTransactions($userId);

        $this->view('templates/header', $data);
        $this->view('student/index', $data);
        $this->view('templates/footer');
    }
}