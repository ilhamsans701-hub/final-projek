<?php

class FinancialAdvisor {

    public static function analyze($income, $expense, $topCategory)
    {
        // 1. Cek Kesehatan Dasar (Rasio Pengeluaran vs Pemasukan)
        // Hindari division by zero jika income 0
        if ($income <= 0) {
            return [
                'status' => 'danger',
                'message' => '🚨 <strong>Darurat!</strong> Anda belum mencatat pemasukan sama sekali bulan ini. Segera catat sumber danamu!'
            ];
        }

        $ratio = ($expense / $income) * 100;
        
        // Skenario 1: Lebih Besar Pasak daripada Tiang (Boros Parah)
        if ($ratio > 100) {
            return [
                'status' => 'dark',
                'message' => '💀 <strong>BAHAYA!</strong> Pengeluaranmu melebihi pemasukan ('. number_format($ratio, 1) . '%). Kamu sedang defisit. Hentikan semua pengeluaran sekunder!'
            ];
        }

        // Skenario 2: Lampu Kuning (Hampir Habis)
        if ($ratio > 80) {
            $catName = $topCategory ? $topCategory['name'] : 'Jajan';
            return [
                'status' => 'warning',
                'message' => '⚠️ <strong>Hati-hati!</strong> Kamu sudah menghabiskan 80% anggaran. Kategori terborosmu adalah <u>' . $catName . '</u>. Coba rem dulu minggu ini.'
            ];
        }

        // Skenario 3: Hemat (Safe)
        // Kalau aman, kita kasih saran spesifik berdasarkan kategori terboros
        if ($topCategory) {
            $catName = strtolower($topCategory['name']);
            
            // Logika Spesifik (Context Aware)
            if (strpos($catName, 'makan') !== false) {
                return [
                    'status' => 'info',
                    'message' => '💡 Keuanganmu aman. Tapi pengeluaran terbesarmu di <strong>Makanan</strong>. Coba bawa bekal atau masak sendiri untuk menabung lebih banyak.'
                ];
            }
            if (strpos($catName, 'game') !== false || strpos($catName, 'hiburan') !== false) {
                return [
                    'status' => 'info',
                    'message' => '💡 Keuangan stabil. Cuma ingat, top-up <strong>Game</strong> jangan keseringan ya. Tabung buat beli laptop baru!'
                ];
            }
            if (strpos($catName, 'transport') !== false) {
                return [
                    'status' => 'info',
                    'message' => '💡 Transportasi jadi pengeluaran utamamu. Sudah coba cari tebengan atau jalan kaki jika dekat?'
                ];
            }
        }

        // Skenario 4: Sempurna
        return [
            'status' => 'success',
            'message' => '✅ <strong>Luar Biasa!</strong> Manajemen keuanganmu sangat sehat. Teruskan kebiasaan baik ini, Sultan masa depan!'
        ];
    }
}