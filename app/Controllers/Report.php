<?php

class Report extends Controller {
    
    public function index()
    {
        // Halaman Filter (Pilih Bulan & Tahun)
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $data['judul'] = 'Laporan Keuangan';
        $data['user'] = $_SESSION['username'];
        
        $this->view('templates/header', $data);
        $this->view('report/index', $data);
        $this->view('templates/footer');
    }

    public function print()
    {
        // Halaman Cetak (Kertas A4)
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        $month = $_POST['month'];
        $year = $_POST['year'];
        $userId = $_SESSION['user_id'];

        $data['judul'] = 'Laporan Transaksi';
        $data['month_name'] = date("F", mktime(0, 0, 0, $month, 10)); // Ubah angka bulan jadi Nama (January, etc)
        $data['year'] = $year;
        $data['user'] = $_SESSION['username']; // Nama User untuk Tanda Tangan
        
        // Ambil Data
        $data['transactions'] = $this->model('Transaction_model')->getTransactionsByMonth($userId, $month, $year);

        // Load View Khusus Print (Tanpa Header/Footer Website)
        $this->view('report/print', $data);
    }

    public function export_csv()
    {
        // Fitur Download CSV (Excel) - Backend Logic Murni
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        $month = $_POST['month'];
        $year = $_POST['year'];
        $userId = $_SESSION['user_id'];

        $transactions = $this->model('Transaction_model')->getTransactionsByMonth($userId, $month, $year);

        // Set Header agar browser download file
        $filename = "Laporan_Keuangan_" . date('Y-m') . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        // Buka Output Stream
        $output = fopen('php://output', 'w');

        // Header Kolom CSV
        fputcsv($output, ['Tanggal', 'Tipe', 'Kategori', 'Deskripsi', 'Nominal (IDR)', 'Mata Uang Asli', 'Nominal Asli']);

        // Isi Data
        foreach ($transactions as $trx) {
            fputcsv($output, [
                $trx['transaction_date'],
                $trx['type'],
                $trx['category_name'],
                $trx['description'],
                $trx['amount'],
                $trx['currency_code'],
                $trx['amount_origin']
            ]);
        }

        fclose($output);
        exit;
    }
}