<?php
define('BASEURL', 'http://localhost/mymoney/public');

// DATABASE CONFIG (Ambil dari .env)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'mymoney_db');

define('EXCHANGE_RATE_KEY', getenv('EXCHANGE_RATE_KEY'));