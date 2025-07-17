<?php
namespace App\Middleware;

class AuthMiddleware
{
    public function handle(array $params = []): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        // Verificar rol si se especificó
        if (isset($params['role']) && $_SESSION['user']->role !== $params['role']) {
            http_response_code(403);
            echo 'Acceso no autorizado';
            exit;
        }
        
        return true;
    }
}