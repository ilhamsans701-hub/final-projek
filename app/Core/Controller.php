<?php
class Controller {
    // Fungsi untuk menampilkan View (HTML)
    public function view($view, $data = [])
    {
        require_once '../views/' . $view . '.php';
    }

    // Fungsi untuk memanggil Model (Database)
    public function model($model)
    {
        require_once '../app/Models/' . $model . '.php';
        return new $model;
    }
}