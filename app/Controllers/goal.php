<?php

class Goal extends Controller {
    
    public function index()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anak') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        $data = [
            'judul' => 'Target Tabungan Saya',
            'user' => $_SESSION['username'],
            'goals' => $this->model('Goal_model')->getUserGoals($userId),
            'current_page' => 'goal'
        ];

        $this->view('templates/header_dashboard', $data);
        $this->view('goal/index', $data);
        $this->view('templates/footer_dashboard');
    }

    public function store()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        // 1. Validasi Input Dasar
        if (empty($_POST['title']) || empty($_POST['target_amount']) || empty($_POST['deadline'])) {
            Flasher::setFlash('gagal', 'Semua kolom wajib diisi', 'danger');
            header('Location: ' . BASEURL . '/goal');
            exit;
        }

        // 2. Bersihkan Format Uang (Rp 1.000.000 -> 1000000)
        $amount = $_POST['target_amount'];
        $amount = str_replace('.', '', $amount); // Hapus titik ribuan
        $amount = str_replace(',', '.', $amount); // Ubah koma jadi titik desimal (jaga-jaga)

        // 3. Siapkan Data
        $data = [
            'user_id'       => $_SESSION['user_id'],
            'title'         => $_POST['title'],
            'target_amount' => $amount, // Hasil yang sudah dibersihkan
            'current_amount'=> 0,
            'deadline'      => $_POST['deadline']
        ];

        // 4. Kirim ke Model
        if ($this->model('Goal_model')->addGoal($data)) {
            Flasher::setFlash('berhasil', 'Target tabungan berhasil ditambahkan', 'success');
        } else {
            Flasher::setFlash('gagal', 'Gagal menambahkan target', 'danger');
        }
        
        header('Location: ' . BASEURL . '/goal');
        exit;
    }

    public function add_progress($goalId)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        $amount = str_replace('.', '', $_POST['amount']);
        $amount = str_replace(',', '.', $amount);
        
        if ($this->model('Goal_model')->updateGoalProgress($goalId, $amount)) {
            // Cek apakah sudah mencapai target
            $goal = $this->getGoalById($goalId);
            if ($goal['current_amount'] >= $goal['target_amount']) {
                $this->model('Goal_model')->completeGoal($goalId);
                Flasher::setFlash('berhasil', 'Selamat! Target tabungan tercapai! 🎉', 'success');
            } else {
                Flasher::setFlash('berhasil', 'Progress berhasil ditambahkan', 'success');
            }
        }
        
        header('Location: ' . BASEURL . '/goal');
        exit;
    }

    public function complete($goalId)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if ($this->model('Goal_model')->completeGoal($goalId)) {
            Flasher::setFlash('berhasil', 'Target ditandai sebagai selesai', 'success');
        }
        
        header('Location: ' . BASEURL . '/goal');
        exit;
    }

    public function delete($goalId)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if ($this->model('Goal_model')->deleteGoal($goalId, $_SESSION['user_id'])) {
            Flasher::setFlash('berhasil', 'Target dihapus', 'warning');
        }
        
        header('Location: ' . BASEURL . '/goal');
        exit;
    }

    private function getGoalById($goalId)
    {
        $db = new Database;
        $db->query("SELECT * FROM goals WHERE id = :id");
        $db->bind('id', $goalId);
        return $db->single();
    }
}