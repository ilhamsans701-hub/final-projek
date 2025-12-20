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

    // Di Transaction_model.php, perbaiki method getSummary():
public function getSummary($userId)
{
    // Query yang mengembalikan 0 jika NULL
    $this->db->query("SELECT 
        COALESCE(SUM(CASE WHEN type = 'income' AND is_deleted = 0 THEN amount ELSE 0 END), 0) as total_income,
        COALESCE(SUM(CASE WHEN type = 'expense' AND is_deleted = 0 THEN amount ELSE 0 END), 0) as total_expense,
        COALESCE(COUNT(CASE WHEN type = 'income' AND is_deleted = 0 THEN 1 END), 0) as income_count,
        COALESCE(COUNT(CASE WHEN type = 'expense' AND is_deleted = 0 THEN 1 END), 0) as expense_count
        FROM " . $this->table . " WHERE user_id = :user_id");
        
    $this->db->bind('user_id', $userId);
    return $this->db->single();
}

    public function getCategories()
    {
        $this->db->query("SELECT * FROM categories ORDER BY type ASC, name ASC");
        return $this->db->resultSet();
    }

    public function addTransaction($data)
    {
        $query = "INSERT INTO transactions 
                    (user_id, category_id, type, description, amount, amount_origin, currency_code, exchange_rate, transaction_date) 
                    VALUES 
                    (:user_id, :category_id, :type, :description, :amount, :amount_origin, :currency_code, :exchange_rate, :transaction_date)";
        
        $this->db->query($query);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('amount', $data['amount']); // Nilai IDR
        $this->db->bind('amount_origin', $data['amount_origin']); // Nilai Asli
        $this->db->bind('currency_code', $data['currency_code']);
        $this->db->bind('exchange_rate', $data['exchange_rate']);
        $this->db->bind('transaction_date', $data['transaction_date']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getMonthlyStats($userId)
    {
        // Query Advance: Grouping data berdasarkan Bulan untuk tahun saat ini
        // Mengembalikan array: Bulan, Tipe, Total
        $query = "SELECT 
                    MONTH(transaction_date) as month, 
                    type, 
                    SUM(amount) as total 
                    FROM " . $this->table . " 
                    WHERE user_id = :user_id 
                    AND YEAR(transaction_date) = YEAR(CURDATE()) 
                    AND is_deleted = 0
                    GROUP BY MONTH(transaction_date), type
                    ORDER BY month ASC";
                
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function getTopExpenseCategory($userId)
    {
        // Query untuk mencari 1 kategori dengan total pengeluaran terbesar bulan ini
        $query = "SELECT c.name, SUM(t.amount) as total 
                    FROM " . $this->table . " t
                    JOIN categories c ON t.category_id = c.id
                    WHERE t.user_id = :user_id 
                    AND t.type = 'expense'
                    AND t.is_deleted = 0
                    AND MONTH(t.transaction_date) = MONTH(CURDATE())
                    AND YEAR(t.transaction_date) = YEAR(CURDATE())
                    GROUP BY c.name 
                    ORDER BY total DESC 
                    LIMIT 1";
                
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->single();
    }

    public function getTransactionById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateTransaction($data)
    {
        $query = "UPDATE transactions SET 
                    category_id = :category_id,
                    type = :type,
                    description = :description,
                    amount = :amount,
                    amount_origin = :amount_origin,
                    currency_code = :currency_code,
                    exchange_rate = :exchange_rate,
                    transaction_date = :transaction_date
                  WHERE id = :id AND user_id = :user_id"; // Pastikan user_id dicek agar tidak edit punya orang lain

        $this->db->query($query);
        
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('amount_origin', $data['amount_origin']);
        $this->db->bind('currency_code', $data['currency_code']);
        $this->db->bind('exchange_rate', $data['exchange_rate']);
        $this->db->bind('transaction_date', $data['transaction_date']);
        $this->db->bind('id', $data['id']);
        $this->db->bind('user_id', $data['user_id']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteTransaction($id, $userId)
    {
        // Soft Delete (Flagging is_deleted = 1) agar data tidak hilang permanen (Audit Trail)
        $query = "UPDATE " . $this->table . " SET is_deleted = 1 WHERE id = :id AND user_id = :user_id";
        
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $userId);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getTransactionsByMonth($userId, $month, $year)
    {
        $query = "SELECT t.*, c.name as category_name 
                    FROM " . $this->table . " t 
                    JOIN categories c ON t.category_id = c.id 
                    WHERE t.user_id = :user_id 
                    AND MONTH(t.transaction_date) = :month 
                    AND YEAR(t.transaction_date) = :year 
                    AND t.is_deleted = 0 
                    ORDER BY t.transaction_date ASC";
        
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        $this->db->bind('month', $month);
        $this->db->bind('year', $year);
        return $this->db->resultSet();
    }

    public function getRecentTransactions($userId, $limit = null)
    {
        $query = "SELECT t.*, c.name as category_name, c.icon as category_icon 
                FROM transactions t 
                LEFT JOIN categories c ON t.category_id = c.id 
                WHERE t.user_id = :user_id 
                AND t.is_deleted = 0 
                ORDER BY t.transaction_date DESC, t.created_at DESC";
        
        // Tambahkan LIMIT hanya jika parameter limit diberikan
        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }
        
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        
        if ($limit !== null) {
            $this->db->bind('limit', $limit, PDO::PARAM_INT);
        }
        
        return $this->db->resultSet();
    }

    // Tambahkan method ini di Transaction_model.php
    public function getTotalByType($userId, $type) {
        $query = "SELECT COALESCE(SUM(amount), 0) as total 
                FROM transactions 
                WHERE user_id = :user_id AND type = :type";
        
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        $this->db->bind('type', $type);
        $result = $this->db->single();
        return $result['total'];
    }

    // Method untuk mendapatkan ringkasan total
    public function getSummaryTotals($userId) {
        $query = "SELECT 
                    COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
                    COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense,
                    COUNT(*) as total_transactions
                FROM transactions 
                WHERE user_id = :user_id";
        
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->single();
    }
}