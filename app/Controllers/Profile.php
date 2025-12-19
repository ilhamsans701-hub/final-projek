<?php 

class Profile extends Controller {
    
    public function index()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $data['judul'] = 'Profil Saya';
        $data['user'] = $_SESSION['username']; 
        $data['profile_user'] = $this->model('User_model')->getUserById($_SESSION['user_id']); 
        $data['current_page'] = 'profile';

        $this->view('templates/header_dashboard', $data);
        $this->view('profile/index', $data);
        $this->view('templates/footer_dashboard');
    }

    public function update()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        // 1. Ambil data standar
        $id = $_POST['id'];
        $username = htmlspecialchars($_POST['username']); // Sanitasi input
        $email = htmlspecialchars($_POST['email']);
        $fotoLama = $_POST['fotoLama'];

        // 2. Logika Upload Foto
        if ($_FILES['photo']['error'] === 4) {
            $fotoBaru = $fotoLama;
        } else {
            $fotoBaru = $this->uploadFoto();
            // FIX: Jika upload gagal, redirect kembali (jangan return false doang)
            if (!$fotoBaru) {
                header('Location: ' . BASEURL . '/profile');
                exit;
            }
        }

        // 3. FIX: Logika Update Password
        $password = null; // Default null (tidak ganti password)
        
        // Cek jika user mengisi password baru
        if (!empty($_POST['new_password'])) {
            // Validasi: Password baru harus sama dengan konfirmasi
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                Flasher::setFlash('gagal', 'Konfirmasi password tidak cocok!', 'danger');
                header('Location: ' . BASEURL . '/profile');
                exit;
            }
            
            // Validasi panjang password
            if (strlen($_POST['new_password']) < 6) {
                Flasher::setFlash('gagal', 'Password minimal 6 karakter!', 'danger');
                header('Location: ' . BASEURL . '/profile');
                exit;
            }

            // Hash password baru
            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        }

        // 4. Susun data
        $data = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'photo' => $fotoBaru,
            'password' => $password // Kirim password (bisa null)
        ];

        // 5. Kirim ke Model (Pastikan Model bisa handle password null)
        if ($this->model('User_model')->updateProfile($data) > 0) {
            $_SESSION['username'] = $username; 
            $_SESSION['user_profile_photo'] = $fotoBaru;
            Flasher::setFlash('berhasil', 'Profil berhasil diupdate', 'success');
        } else {
            // Jika tidak ada perubahan data, anggap info saja
            Flasher::setFlash('info', 'Tidak ada perubahan data', 'info');
        }
        
        header('Location: ' . BASEURL . '/profile');
        exit;
    }

    public function uploadFoto()
    {
        $namaFile = $_FILES['photo']['name'];
        $ukuranFile = $_FILES['photo']['size'];
        $error = $_FILES['photo']['error'];
        $tmpName = $_FILES['photo']['tmp_name'];

        // Cek ekstensi
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            // FIX: Gunakan Flasher, jangan echo script alert di controller
            Flasher::setFlash('gagal', 'File bukan gambar valid (jpg/png)!', 'danger');
            return false;
        }

        if ($ukuranFile > 2000000) {
            Flasher::setFlash('gagal', 'Ukuran gambar terlalu besar (Max 2MB)!', 'danger');
            return false;
        }

        // Generate nama unik
        $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
        $tujuan = $_SERVER['DOCUMENT_ROOT'] . '/mymoney/public/img/profile/' . $namaFileBaru;

        if(move_uploaded_file($tmpName, $tujuan)) {
            return $namaFileBaru;
        } else {
            // Fallback jika path absolute gagal, coba relative (untuk XAMPP biasanya)
            move_uploaded_file($tmpName, 'img/profile/' . $namaFileBaru);
            return $namaFileBaru;
        }
    }
    
    public function delete()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        if ($this->model('User_model')->deleteUser($_SESSION['user_id']) > 0) {
            // Logout user
            session_destroy();
            header('Location: ' . BASEURL . '/auth');
            exit;
        } else {
            Flasher::setFlash('gagal', 'menghapus akun', 'danger');
            header('Location: ' . BASEURL . '/profile');
            exit;
        }
    }
}