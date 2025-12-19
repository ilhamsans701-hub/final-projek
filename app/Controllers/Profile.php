<?php 

class Profile extends Controller {
    
    public function index()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['anak', 'orangtua'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        // Set judul berdasarkan role
        $role = $_SESSION['role'];
        $judulMap = [
            'anak' => 'Profil Saya',
            'orangtua' => 'Profil Orang Tua'
        ];
        
        $data['judul'] = $judulMap[$role];
        $data['user'] = $_SESSION['username']; 
        $data['profile_user'] = $this->model('User_model')->getUserById($_SESSION['user_id']); 
        $data['current_page'] = 'profile';
        $data['role'] = $role;

        // Load view berdasarkan role
        $viewFile = 'profile/' . $role . '/index';
        
        // Tentukan header & footer berdasarkan role
        $headerTemplate = ($role == 'orangtua') ? 'templates/header_parent' : 'templates/header_dashboard';
        $footerTemplate = ($role == 'orangtua') ? 'templates/footer_parent' : 'templates/footer_dashboard';
        
        $this->view($headerTemplate, $data);
        $this->view($viewFile, $data);
        $this->view($footerTemplate);
    }

    public function update()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();

        // Cek session dan role
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['anak', 'orangtua'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        // 1. Ambil data standar
        $id = $_POST['id'];
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $fotoLama = $_POST['fotoLama'];

        // 2. Logika Upload Foto
        if ($_FILES['photo']['error'] === 4) {
            $fotoBaru = $fotoLama;
        } else {
            $fotoBaru = $this->uploadFoto();
            if (!$fotoBaru) {
                header('Location: ' . BASEURL . '/profile');
                exit;
            }
        }

        // 3. Logika Update Password
        $password = null;
        
        if (!empty($_POST['new_password'])) {
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                Flasher::setFlash('gagal', 'Konfirmasi password tidak cocok!', 'danger');
                header('Location: ' . BASEURL . '/profile');
                exit;
            }
            
            if (strlen($_POST['new_password']) < 6) {
                Flasher::setFlash('gagal', 'Password minimal 6 karakter!', 'danger');
                header('Location: ' . BASEURL . '/profile');
                exit;
            }

            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        }

        // 4. Susun data
        $data = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'photo' => $fotoBaru,
            'password' => $password
        ];

        // 5. Update ke Model
        if ($this->model('User_model')->updateProfile($data) > 0) {
            $_SESSION['username'] = $username; 
            $_SESSION['user_profile_photo'] = $fotoBaru;
            Flasher::setFlash('berhasil', 'Profil berhasil diupdate', 'success');
        } else {
            Flasher::setFlash('info', 'Tidak ada perubahan data', 'info');
        }
        
        // Redirect berdasarkan role untuk tampilan yang benar
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
            move_uploaded_file($tmpName, 'img/profile/' . $namaFileBaru);
            return $namaFileBaru;
        }
    }
    
    public function delete()
    {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['anak', 'orangtua'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        if ($this->model('User_model')->deleteUser($_SESSION['user_id']) > 0) {
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