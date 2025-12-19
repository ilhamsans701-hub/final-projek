<?php

class Parent_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Mengambil data user (Orang Tua) beserta family_code nya
    public function getParentData($userId)
    {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind('id', $userId);
        return $this->db->single();
    }

    // Mencari anak yang punya family_code SAMA dengan Orang Tua
    public function getChildren($familyCode)
    {
        // Cari user dengan role 'anak' dan kode keluarga yang sama
        $this->db->query("SELECT * FROM users WHERE role = 'anak' AND family_code = :code ORDER BY username ASC");
        $this->db->bind('code', $familyCode);
        return $this->db->resultSet();
    }

    // Mengambil data anak berdasarkan ID (dengan validasi family_code)
    public function getChildById($childId, $familyCode)
    {
        $this->db->query("SELECT * FROM users WHERE id = :id AND role = 'anak' AND family_code = :code");
        $this->db->bind('id', $childId);
        $this->db->bind('code', $familyCode);
        return $this->db->single();
    }

    // Mengambil ringkasan keuangan spesifik milik Anak
    public function getChildSummary($childId)
    {
        $this->db->query("SELECT 
            SUM(CASE WHEN type = 'income' AND is_deleted = 0 THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = 'expense' AND is_deleted = 0 THEN amount ELSE 0 END) as total_expense,
            COUNT(CASE WHEN type = 'income' AND is_deleted = 0 THEN 1 END) as income_count,
            COUNT(CASE WHEN type = 'expense' AND is_deleted = 0 THEN 1 END) as expense_count
            FROM transactions WHERE user_id = :child_id");
            
        $this->db->bind('child_id', $childId);
        return $this->db->single();
    }

    // Mengambil semua transaksi anak
    public function getChildTransactions($childId, $limit = null)
    {
        $query = "SELECT t.*, c.name as category_name, c.icon as category_icon 
                  FROM transactions t 
                  JOIN categories c ON t.category_id = c.id 
                  WHERE t.user_id = :child_id AND t.is_deleted = 0 
                  ORDER BY t.transaction_date DESC, t.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $this->db->query($query);
        $this->db->bind('child_id', $childId);
        return $this->db->resultSet();
    }

    // Mengambil statistik bulanan anak
    public function getChildMonthlyStats($childId)
    {
        $this->db->query("SELECT 
            MONTH(transaction_date) as month,
            YEAR(transaction_date) as year,
            SUM(CASE WHEN type = 'income' AND is_deleted = 0 THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = 'expense' AND is_deleted = 0 THEN amount ELSE 0 END) as total_expense
            FROM transactions 
            WHERE user_id = :child_id 
            AND YEAR(transaction_date) = YEAR(CURDATE())
            GROUP BY YEAR(transaction_date), MONTH(transaction_date)
            ORDER BY year DESC, month DESC");
            
        $this->db->bind('child_id', $childId);
        return $this->db->resultSet();
    }

    // Mengambil top kategori pengeluaran anak
    public function getChildTopCategories($childId, $type = 'expense', $limit = 5)
    {
        $this->db->query("SELECT 
            c.name as category_name,
            c.icon as category_icon,
            COUNT(t.id) as transaction_count,
            SUM(t.amount) as total_amount
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :child_id 
            AND t.type = :type 
            AND t.is_deleted = 0
            GROUP BY t.category_id
            ORDER BY total_amount DESC
            LIMIT :limit");
            
        $this->db->bind('child_id', $childId);
        $this->db->bind('type', $type);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
}