<?php
namespace App\Controllers;

class ErrorController extends BaseController
{
    public function notFound()
    {
        http_response_code(404);
        $this->render('errors/404', [
            'title' => 'Página no encontrada'
        ]);
    }
    
    public function unauthorized()
    {
        http_response_code(403);
        $this->render('errors/403', [
            'title' => 'Acceso no autorizado'
        ]);
    }
}