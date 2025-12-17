<?php

class Subscription_model {
    private $table = 'subscriptions';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllSubscriptions($userId)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND is_active = 1 ORDER BY due_date ASC");
        $this->db->bind('user_id', $userId);
        $result = $this->db->resultSet();

        // LOGIC ADVANCE: Inject data 'days_left' ke dalam array hasil
        // Agar di View tidak perlu mikir logika tanggal lagi (Separation of Concerns)
        foreach ($result as &$row) {
            $due = new DateTime($row['due_date']);
            $today = new DateTime();
            
            // Set jam ke 00:00:00 agar perbandingan adil (hanya bandingkan tanggal)
            $today->setTime(0, 0, 0);
            $due->setTime(0, 0, 0);

            if ($due < $today) {
                $row['status'] = 'overdue'; // Telat Bayar
                $row['days_left'] = -1 * $today->diff($due)->days; // Minus hari
            } else {
                $diff = $today->diff($due);
                $row['days_left'] = $diff->days;
                
                // Tentukan status urgensi
                if ($row['days_left'] <= 3) {
                    $row['status'] = 'danger'; // H-3 (Merah)
                } elseif ($row['days_left'] <= 7) {
                    $row['status'] = 'warning'; // H-7 (Kuning)
                } else {
                    $row['status'] = 'safe'; // Masih Lama (Hijau)
                }
            }
        }

        return $result;
    }

    public function addSubscription($data)
    {
        $query = "INSERT INTO subscriptions (user_id, service_name, amount, billing_cycle, due_date) 
                  VALUES (:user_id, :service_name, :amount, :billing_cycle, :due_date)";
        
        $this->db->query($query);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('service_name', $data['service_name']);
        $this->db->bind('amount', $data['amount']);
        $this->db->bind('billing_cycle', $data['billing_cycle']);
        $this->db->bind('due_date', $data['due_date']);

        $this->db->execute();
        return $this->db->rowCount();
    }
    
    // Fitur Delete/Mark Inactive (Soft Delete untuk langganan)
    public function deleteSubscription($id)
    {
        $query = "UPDATE subscriptions SET is_active = 0 WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getPendingCount($user_id)
    {
        $query = "SELECT COUNT(*) as count FROM subscriptions 
                WHERE user_id = :user_id 
                AND is_active = 1 
                AND due_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                AND due_date >= CURDATE()"; // Hanya yang aktif dan jatuh tempo 3 hari ke depan
        $this->db->query($query);
        $this->db->bind('user_id', $user_id);
        $result = $this->db->single();
        return $result['count'];
    }
}