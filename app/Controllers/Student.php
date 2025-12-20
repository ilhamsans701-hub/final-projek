<?php

require_once '../app/core/FinancialAdvisor.php';

class Student extends Controller {
    public function index()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $transModel = $this->model('Transaction_model');
        
        $data['judul'] = 'Dashboard Mahasiswa';
        $data['user'] = $_SESSION['username'];
        $data['summary'] = $transModel->getSummary($userId);
        
        // **PERUBAHAN PENTING:** Ambil SEMUA transaksi untuk DataTables, bukan hanya 5
        $data['transactions'] = $transModel->getRecentTransactions($userId); // Hapus parameter limit

        $stats = $transModel->getMonthlyStats($userId);
        $incomeData = array_fill(0, 12, 0);
        $expenseData = array_fill(0, 12, 0);

        foreach ($stats as $row) {
            $index = $row['month'] - 1;
            if ($row['type'] == 'income') {
                $incomeData[$index] = (float) $row['total'];
            } else {
                $expenseData[$index] = (float) $row['total'];
            }
        }

        $data['chart_income'] = json_encode($incomeData);
        $data['chart_expense'] = json_encode($expenseData);
        $totalIncome = $data['summary']['total_income'];
        $totalExpense = $data['summary']['total_expense'];
        $topCategory = $transModel->getTopExpenseCategory($userId);
        $data['advice'] = FinancialAdvisor::analyze($totalIncome, $totalExpense, $topCategory);
        $data['current_page'] = 'student';

        $this->view('templates/header_dashboard', $data);
        $this->view('student/index', $data);
        $this->view('templates/footer_dashboard'); 
    }

    public function create()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $transModel = $this->model('Transaction_model');
        
        $data['judul'] = 'Tambah Transaksi';
        $data['user'] = $_SESSION['username'];
        $data['categories'] = $transModel->getCategories();
        
        $data['monthly_stats'] = $transModel->getMonthlyStats($userId, date('m'), date('Y'));
        $data['current_page'] = 'create';

        $this->view('templates/header_dashboard', $data);
        $this->view('student/create', $data);
        $this->view('templates/footer_dashboard'); 
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

    public function delete($id)
    {
        // Cek login... (bisa dibuat private method biar DRY, tapi copy dulu gapapa)
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if ($this->model('Transaction_model')->deleteTransaction($id, $_SESSION['user_id']) > 0) {
            Flasher::setFlash('berhasil', 'Transaksi berhasil dihapus', 'warning');
        } else {
            Flasher::setFlash('gagal', 'menghapus transaksi', 'danger');
        }
        header('Location: ' . BASEURL . '/student');
        exit;
    }

    public function edit($id)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        $data['judul'] = 'Edit Transaksi';
        $data['user'] = $_SESSION['username'];
        $data['categories'] = $this->model('Transaction_model')->getCategories();
        
        // Ambil data transaksi lama
        $data['trx'] = $this->model('Transaction_model')->getTransactionById($id);

        // Security: Cek apakah transaksi ini milik user yang login?
        if(!$data['trx'] || $data['trx']['user_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASEURL . '/student');
            exit;
        }

        $this->view('templates/header_dashboard', $data);
        $this->view('student/edit', $data); // Kita buat view ini sebentar lagi
        $this->view('templates/footer_dashboard');
    }

    public function update()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        // 1. Ambil Data
        $userId = $_SESSION['user_id'];
        $amountOrigin = str_replace('.', '', $_POST['amount']);
        $currency = $_POST['currency'];
        $rate = $_POST['exchange_rate_old']; // Default pakai rate lama

        // 2. Cek apakah mata uang berubah? Jika ya, ambil kurs baru
        // (Opsional: Bisa dipaksa update kurs, tapi disini kita pakai logika sederhana dulu)
        if ($currency !== 'IDR') {
             $rate = Currency::getRate($currency, 'IDR');
             $amountIDR = $amountOrigin * $rate;
        } else {
             $rate = 1;
             $amountIDR = $amountOrigin;
        }

        $data = [
            'id' => $_POST['id'],
            'user_id' => $userId,
            'category_id' => $_POST['category_id'],
            'type' => $_POST['type'],
            'description' => $_POST['description'],
            'amount' => $amountIDR,
            'amount_origin' => $amountOrigin,
            'currency_code' => $currency,
            'exchange_rate' => $rate,
            'transaction_date' => $_POST['date']
        ];

        if ($this->model('Transaction_model')->updateTransaction($data) > 0) {
            Flasher::setFlash('berhasil', 'Transaksi berhasil diupdate', 'success');
        } else {
            // Jika rowCount 0 (tidak ada perubahan), tetap anggap sukses/info
            Flasher::setFlash('info', 'Tidak ada data yang berubah', 'info');
        }
        
        header('Location: ' . BASEURL . '/student');
        exit;
    }

    public function add_progress($goalId)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        // Cek login
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Bersihkan format uang
            $amount = str_replace('.', '', $_POST['amount']);
            $amount = str_replace(',', '.', $amount); // Ubah koma jadi titik desimal
            
            // Panggil Model (Model sudah diperbaiki menggunakan rowCount)
            if ($this->model('Goal_model')->updateGoalProgress($goalId, $amount)) {
                
                // Cek auto-complete
                $goalModel = $this->model('Goal_model');
                $goal = $goalModel->getGoalById($goalId);
                
                if ($goal && $goal['current_amount'] >= $goal['target_amount']) {
                    $goalModel->completeGoal($goalId);
                    Flasher::setFlash('berhasil', 'Selamat! Target tabungan tercapai! 🎉', 'success');
                } else {
                    Flasher::setFlash('berhasil', 'Progress berhasil ditambahkan', 'success');
                }
            } else {
                // Jika rowCount 0 (misal nambah 0 rupiah), ini akan terpanggil
                Flasher::setFlash('gagal', 'Gagal menambahkan progress (Data tidak berubah)', 'danger');
            }
        }
        
        header('Location: ' . BASEURL . '/student');
        exit;
    }

    public function complete_goal($goalId)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        
        if ($this->model('Goal_model')->completeGoal($goalId)) {
            Flasher::setFlash('berhasil', 'Target ditandai sebagai selesai', 'success');
        } else {
            // Jika target sudah selesai sebelumnya, rowCount akan 0, jadi masuk sini
            Flasher::setFlash('info', 'Target sudah selesai atau tidak ada perubahan', 'info');
        }
        
        header('Location: ' . BASEURL . '/student');
        exit;
    }

    // Method helper untuk AJAX (opsional, untuk tanpa refresh halaman)
    public function get_goals_data()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        $goalModel = $this->model('Goal_model');
        $activeGoals = $goalModel->getActiveGoals($_SESSION['user_id'], 3);
        
        header('Content-Type: application/json');
        echo json_encode($activeGoals);
        exit;
    }

    // Tambahkan juga method untuk update statistik (opsional)
    public function update_stats()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $transModel = $this->model('Transaction_model');
        
        $data['summary'] = $transModel->getSummary($userId);
        $data['transactions'] = $transModel->getRecentTransactions($userId);
        
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}