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

        $this->view('templates/header_parent', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer_parent');
    }

    // Halaman Detail Anak
    public function detail($childId = null)
    {
        // 1. Cek Sesi
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'orangtua') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        // 2. Validasi parameter
        if (!$childId || !is_numeric($childId)) {
            Flasher::setFlash('error', 'ID anak tidak valid', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $parentModel = $this->model('Parent_model');
        $userModel = $this->model('User_model');

        // 3. Ambil data orang tua
        $userData = $parentModel->getParentData($userId);
        $familyCode = $userData['family_code'];

        // 4. Ambil data anak dengan validasi family_code
        $child = $parentModel->getChildById($childId, $familyCode);
        
        if (!$child) {
            Flasher::setFlash('error', 'Data anak tidak ditemukan atau tidak terhubung', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        // 5. Ambil semua data yang dibutuhkan
        $summary = $parentModel->getChildSummary($childId);
        $saldo = $summary['total_income'] - $summary['total_expense'];
        
        $monthlyStats = $parentModel->getChildMonthlyStats($childId);
        $transactions = $parentModel->getChildTransactions($childId, 50); // Limit 50 transaksi
        $topExpenseCategories = $parentModel->getChildTopCategories($childId, 'expense', 5);
        $topIncomeCategories = $parentModel->getChildTopCategories($childId, 'income', 3);

        // 6. Siapkan data untuk chart
        $incomeData = array_fill(0, 12, 0);
        $expenseData = array_fill(0, 12, 0);
        
        foreach ($monthlyStats as $stat) {
            $index = $stat['month'] - 1;
            $incomeData[$index] = (float) $stat['total_income'];
            $expenseData[$index] = (float) $stat['total_expense'];
        }

        // 7. Siapkan data untuk view
        $data = [
            'judul' => 'Detail Keuangan - ' . htmlspecialchars($child['username']),
            'user' => $_SESSION['username'],
            'child' => $child,
            'summary' => $summary,
            'saldo' => $saldo,
            'transactions' => $transactions,
            'top_expense_categories' => $topExpenseCategories,
            'top_income_categories' => $topIncomeCategories,
            'chart_income' => json_encode($incomeData),
            'chart_expense' => json_encode($expenseData),
            'current_page' => 'dashboard'
        ];

        // 8. Load view
        $this->view('templates/header_parent', $data);
        $this->view('dashboard/detail', $data);
        $this->view('templates/footer_parent');
    }

    // Helper function untuk export data anak (CSV)
    public function export_child($childId)
    {
        // 1. Cek Sesi
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'orangtua') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        // 2. Validasi akses
        $parentModel = $this->model('Parent_model');
        $userData = $parentModel->getParentData($_SESSION['user_id']);
        $child = $parentModel->getChildById($childId, $userData['family_code']);

        if (!$child) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        // 3. Ambil semua transaksi
        $transactions = $parentModel->getChildTransactions($childId);

        // 4. Set header untuk download CSV
        $filename = "Laporan_" . $child['username'] . "_" . date('Y-m-d') . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // 5. Output CSV
        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Tanggal', 
            'Tipe', 
            'Kategori', 
            'Deskripsi', 
            'Nominal (IDR)', 
            'Mata Uang', 
            'Nominal Asli',
            'Kurs'
        ]);

        foreach ($transactions as $trx) {
            fputcsv($output, [
                $trx['transaction_date'],
                $trx['type'] == 'income' ? 'Pemasukan' : 'Pengeluaran',
                $trx['category_name'],
                $trx['description'],
                number_format($trx['amount'], 0, ',', '.'),
                $trx['currency_code'],
                $trx['amount_origin'],
                $trx['exchange_rate']
            ]);
        }

        fclose($output);
        exit;
    }

    // Helper function (Private) untuk ambil transaksi anak
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