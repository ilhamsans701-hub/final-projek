<?php

class Subscription extends Controller {
    
    public function index()
    {
        // Security Check
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $data['judul'] = 'Kelola Tagihan Rutin';
        $data['user'] = $_SESSION['username'];
        
        // Ambil data yang sudah diproses logic tanggalnya
        $data['subscriptions'] = $this->model('Subscription_model')->getAllSubscriptions($_SESSION['user_id']);
        $data['current_page'] = 'subscription';

        $subscriptionModel = $this->model('Subscription_model');
        $data['pending_count'] = $subscriptionModel->getPendingCount($_SESSION['user_id']);

        $this->view('templates/header_dashboard', $data);
        $this->view('subscription/index', $data);
        $this->view('templates/footer_dashboard');
    }

    public function store()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        // Sanitize amount - HAPUS SEMUA KARAKTER NON-DIGIT
        $amount = preg_replace('/[^0-9]/', '', $_POST['amount']);
        
        // Pastikan amount numeric dan lebih dari 0
        if (!is_numeric($amount) || $amount <= 0) {
            Flasher::setFlash('gagal', 'Biaya harus berupa angka positif', 'danger');
            header('Location: ' . BASEURL . '/subscription');
            exit;
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'service_name' => $_POST['service_name'],
            'amount' => $amount, // Nilai sudah bersih (hanya angka)
            'billing_cycle' => $_POST['billing_cycle'],
            'due_date' => $_POST['due_date']
        ];

        if ($this->model('Subscription_model')->addSubscription($data) > 0) {
            Flasher::setFlash('berhasil', 'Tagihan rutin berhasil ditambahkan', 'success');
        } else {
            Flasher::setFlash('gagal', 'Gagal menambahkan tagihan', 'danger');
        }

        header('Location: ' . BASEURL . '/subscription');
        exit;
    }
    
    public function delete($id)
    {
        if ($this->model('Subscription_model')->deleteSubscription($id) > 0) {
            Flasher::setFlash('berhasil', 'Langganan dihentikan', 'warning');
        }
        header('Location: ' . BASEURL . '/subscription');
        exit;
    }
}