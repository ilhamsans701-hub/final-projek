<?php
class App {
    protected $controller = 'Home'; 
    protected $method = 'index';    
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // 1. Cek Controller
        if(isset($url[0])) {
            if(file_exists('../app/Controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        require_once '../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Cek Method
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Kelola Parameter
        if(!empty($url)) {
            $this->params = array_values($url);
        }

        // Jalankan Controller & Method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}