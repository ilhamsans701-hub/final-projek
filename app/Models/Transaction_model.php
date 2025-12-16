<?php

class Transaction_model {
    private $table = 'transactions';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllTransactions($userId)
    {
        // Mengambil data transaksi join dengan kategori
        $query = "SELECT t.*, c.name as category_name, c.icon as category_icon 
                    FROM " . $this->table . " t 
                    JOIN categories c ON t.category_id = c.id 
                    WHERE t.user_id = :user_id AND t.is_deleted = 0 
                    ORDER BY t.transaction_date DESC, t.created_at DESC";
        
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function getSummary($userId)
    {
        // Menghitung Total Pemasukan, Pengeluaran, dan Saldo
        $this->db->query("SELECT 
            SUM(CASE WHEN type = 'income' AND is_deleted = 0 THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = 'expense' AND is_deleted = 0 THEN amount ELSE 0 END) as total_expense
            FROM " . $this->table . " WHERE user_id = :user_id");
            
        $this->db->bind('user_id', $userId);
        return $this->db->single();
    }
}