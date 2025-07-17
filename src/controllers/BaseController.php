<?php
namespace App\Controllers;

class BaseController
{
    protected $db;
    
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    
    protected function render(string $view, array $data = [])
    {
        extract($data);
        
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file {$viewPath} not found");
        }
        
        require $viewPath;
    }
    
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}