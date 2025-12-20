<?php

class Budget extends Controller {
    
    public function index()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'orangtua') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $parentModel = $this->model('Parent_model');
        $budgetModel = $this->model('Budget_model');
        
        // Ambil data orang tua dan anak-anaknya
        $userData = $parentModel->getParentData($userId);
        $children = $parentModel->getChildren($userData['family_code']);
        
        // Ambil semua anggaran yang sudah dibuat
        $childrenIds = array_column($children, 'id');
        $budgets = $childrenIds ? $budgetModel->getAllBudgetsForParent($childrenIds) : [];

        $data = [
            'judul' => 'Kelola Anggaran Anak',
            'user' => $_SESSION['username'],
            'children' => $children,
            'budgets' => $budgets,
            'current_month' => date('Y-m'),
            'current_page' => 'budget'
        ];

        $this->view('templates/header_parent', $data);
        $this->view('budget/index', $data);
        $this->view('templates/footer_parent');
    }

    public function store()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        // Validasi input
        $amount = str_replace('.', '', $_POST['amount']);
        $amount = str_replace(',', '.', $amount);
        
        $data = [
            'user_id' => $_POST['child_id'],
            'amount' => $amount,
            'month_year' => $_POST['month_year']
        ];

        if ($this->model('Budget_model')->setBudget($data)) {
            Flasher::setFlash('berhasil', 'Anggaran berhasil diatur', 'success');
        } else {
            Flasher::setFlash('gagal', 'Gagal mengatur anggaran', 'danger');
        }
        
        header('Location: ' . BASEURL . '/budget');
        exit;
    }

    public function delete($id)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if ($this->model('Budget_model')->deleteBudget($id)) {
            Flasher::setFlash('berhasil', 'Anggaran dihapus', 'warning');
        }
        
        header('Location: ' . BASEURL . '/budget');
        exit;
    }

    // API untuk mendapatkan anggaran anak (digunakan di dashboard anak)
    public function get_child_budget($childId)
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        $budget = $this->model('Budget_model')->getChildBudget($childId);
        
        header('Content-Type: application/json');
        echo json_encode($budget);
    }
}