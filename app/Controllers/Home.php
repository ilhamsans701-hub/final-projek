<?php

class Home extends Controller {
    public function index()
    {
        // Cek apakah sudah login
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 'orangtua') {
                header('Location: ' . BASEURL . '/dashboard');
            } else {
                header('Location: ' . BASEURL . '/student');
            }
        } else {
            header('Location: ' . BASEURL . '/auth');
        }
        exit;
    }
}