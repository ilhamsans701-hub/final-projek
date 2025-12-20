<?php

class Budget_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Buat/mengatur anggaran untuk anak
    public function setBudget($data)
    {
        $this->db->query("INSERT INTO budgets (user_id, amount, month_year) 
                        VALUES (:user_id, :amount, :month_year)
                        ON DUPLICATE KEY UPDATE amount = :amount");
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('month_year', $data['month_year']);
        
        return $this->db->execute();
    }

    // Ambil anggaran anak berdasarkan bulan
    public function getChildBudget($childId, $monthYear = null)
    {
        if (!$monthYear) {
            $monthYear = date('Y-m');
        }
        
        $this->db->query("SELECT * FROM budgets 
                        WHERE user_id = :user_id AND month_year = :month_year");
        $this->db->bind('user_id', $childId);
        $this->db->bind('month_year', $monthYear);
        
        return $this->db->single();
    }

    // Ambil semua anggaran yang dibuat orang tua untuk anaknya
    public function getAllBudgetsForParent($childrenIds)
    {
        $placeholders = implode(',', array_fill(0, count($childrenIds), '?'));
        
        $this->db->query("SELECT b.*, u.username as child_name 
                        FROM budgets b
                        JOIN users u ON b.user_id = u.id
                        WHERE b.user_id IN ($placeholders)
                        ORDER BY b.month_year DESC");
        
        foreach ($childrenIds as $key => $id) {
            $this->db->bind($key + 1, $id);
        }
        
        return $this->db->resultSet();
    }

    // Hapus anggaran
    public function deleteBudget($budgetId)
    {
        $this->db->query("DELETE FROM budgets WHERE id = :id");
        $this->db->bind('id', $budgetId);
        return $this->db->execute();
    }
}