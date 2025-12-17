<?php

class Dashboard extends Controller {
    
    public function index()
    {
        // 1. Cek Sesi (Harus Orang Tua)
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'orangtua') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $parentModel = $this->model('Parent_model');
        $transModel = $this->model('Transaction_model'); // Kita pinjam model transaksi

        // 2. Ambil Data Orang Tua
        $userData = $parentModel->getParentData($userId);
        $familyCode = $userData['family_code'];

        $data['judul'] = 'Dashboard Orang Tua';
        $data['user'] = $_SESSION['username'];
        $data['family_code'] = $familyCode;

        // 3. Ambil Data Anak
        // Logic: cari anak yang family_code-nya sama
        $children = $parentModel->getChildren($familyCode);
        
        $data['children_data'] = [];

        // Loop setiap anak untuk ambil detail keuangannya
        foreach ($children as $child) {
            $summary = $parentModel->getChildSummary($child['id']);
            $saldo = $summary['total_income'] - $summary['total_expense'];
            
            // Ambil 5 transaksi terakhir si anak
            $lastTrx = $this->db_get_transactions($child['id']); 

            $data['children_data'][] = [
                'info' => $child,
                'saldo' => $saldo,
                'income' => $summary['total_income'],
                'expense' => $summary['total_expense'],
                'transactions' => $lastTrx
            ];
        }

        $this->view('templates/header', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer');
    }

    // Helper function (Private) untuk ambil transaksi anak lewat Controller ini
    // Sebaiknya pakai Model, tapi untuk mempersingkat waktu kita query manual disini via helper model
    private function db_get_transactions($childId) {
        $db = new Database;
        $query = "SELECT t.*, c.name as category_name, c.icon as category_icon 
                    FROM transactions t 
                    JOIN categories c ON t.category_id = c.id 
                    WHERE t.user_id = :uid AND t.is_deleted = 0 
                    ORDER BY t.transaction_date DESC LIMIT 5";
        $db->query($query);
        $db->bind('uid', $childId);
        return $db->resultSet();
    }
}