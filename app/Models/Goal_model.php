<?php

class Goal_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // File: models/Goal_model.php

    public function addGoal($data)
    {
        $query = "INSERT INTO goals (user_id, title, target_amount, current_amount, deadline, status) 
                VALUES (:user_id, :title, :target_amount, :current_amount, :deadline, 'active')";
        
        $this->db->query($query);
        
        // Casting tipe data (Ini tetap dipajang karena sudah benar)
        $this->db->bind('user_id',        (int) $data['user_id']);
        $this->db->bind('title',          $data['title']);
        $this->db->bind('target_amount',  (float) $data['target_amount']);
        $this->db->bind('current_amount', 0);
        $this->db->bind('deadline',       $data['deadline']);
        
        // --- PERBAIKAN UTAMA DI SINI ---
        
        try {
            // 1. Jalankan perintah
            $this->db->execute();
            
            // 2. Cek apakah ada baris yang berubah/bertambah?
            // Jika rowCount() > 0, berarti data BENAR-BENAR masuk.
            return $this->db->rowCount() > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }

    // Ambil semua goals anak
    public function getUserGoals($userId)
    {
        $this->db->query("SELECT * FROM goals 
                        WHERE user_id = :user_id 
                        ORDER BY 
                            CASE status 
                                WHEN 'active' THEN 1 
                                WHEN 'completed' THEN 2 
                                ELSE 3 
                            END,
                            deadline ASC");
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    // Update progress goal
    public function updateGoalProgress($goalId, $amount)
    {
        $this->db->query("UPDATE goals 
                        SET current_amount = current_amount + :amount
                        WHERE id = :id");
        
        // Paksa jadi float dan int agar Database tidak menolak
        $this->db->bind('amount', (float) $amount);
        $this->db->bind('id',     (int) $goalId);
        
        try {
            $this->db->execute();
            // Cek apakah ada baris yang berubah
            return $this->db->rowCount() > 0; 
        } catch (PDOException $e) {
            return false;
        }
    }

    // --- PERBAIKAN 2: COMPLETE GOAL ---
    public function completeGoal($goalId)
    {
        $this->db->query("UPDATE goals SET status = 'completed' WHERE id = :id");
        
        $this->db->bind('id', (int) $goalId);
        
        try {
            $this->db->execute();
            // Cek apakah ada baris yang berubah
            return $this->db->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Hapus goal
    public function deleteGoal($goalId, $userId)
    {
        $this->db->query("DELETE FROM goals WHERE id = :id AND user_id = :user_id");
        $this->db->bind('id', (int) $goalId);
        $this->db->bind('user_id', (int) $userId);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    // Ambil goal untuk ditampilkan di dashboard anak
    public function getActiveGoals($userId, $limit = 3)
    {
        $this->db->query("SELECT * FROM goals 
                        WHERE user_id = :user_id AND status = 'active'
                        ORDER BY deadline ASC
                        LIMIT :limit");
        $this->db->bind('user_id', $userId);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    // Ambil goal berdasarkan ID
    public function getGoalById($goalId)
    {
        $this->db->query("SELECT * FROM goals WHERE id = :id");
        $this->db->bind('id', $goalId);
        return $this->db->single();
    }
}