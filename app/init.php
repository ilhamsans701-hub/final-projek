<?php
require_once 'Core/Env.php';

try {
    Env::load(dirname(__DIR__) . '/.env');
} catch (Exception $e) {
    // Jika file .env tidak ada, biarkan script jalan
}

// 3. Baru panggil Config dan Core lainnya
require_once 'Config/Config.php';
require_once 'Core/App.php';
require_once 'Core/Controller.php';
require_once 'Core/Database.php';
require_once 'Core/Flasher.php';
require_once 'Core/Currency.php';