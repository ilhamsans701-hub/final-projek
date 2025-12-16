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

    public function create()
    {
        // Cek Sesi (Security Check)
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $data['judul'] = 'Tambah Transaksi';
        $data['user'] = $_SESSION['username'];
        $data['categories'] = $this->model('Transaction_model')->getCategories();

        $this->view('templates/header', $data);
        $this->view('student/create', $data); // Kita buat view ini nanti
        $this->view('templates/footer');
    }

    public function store()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        // 1. Ambil Data Input
        $userId = $_SESSION['user_id'];
        $amountOrigin = str_replace('.', '', $_POST['amount']); // Hapus titik ribuan jika ada
        $currency = $_POST['currency'];
        
        // 2. Logika Konversi Mata Uang
        $rate = 1;
        $amountIDR = $amountOrigin;

        if ($currency !== 'IDR') {
            // Panggil Helper Currency
            $rate = Currency::getRate($currency, 'IDR');
            $amountIDR = $amountOrigin * $rate;
        }

        // 3. Siapkan Data Array
        $data = [
            'user_id' => $userId,
            'category_id' => $_POST['category_id'],
            'type' => $_POST['type'],
            'description' => $_POST['description'],
            'amount' => $amountIDR,       // Yang masuk database selalu konversi IDR
            'amount_origin' => $amountOrigin, // Nominal asli user input
            'currency_code' => $currency,
            'exchange_rate' => $rate,
            'transaction_date' => $_POST['date']
        ];

        // 4. Kirim ke Model
        if ($this->model('Transaction_model')->addTransaction($data) > 0) {
            Flasher::setFlash('berhasil', 'Transaksi berhasil disimpan', 'success');
            header('Location: ' . BASEURL . '/student');
            exit;
        } else {
            Flasher::setFlash('gagal', 'Transaksi gagal disimpan', 'danger');
            header('Location: ' . BASEURL . '/student');
            exit;
        }
    }
}