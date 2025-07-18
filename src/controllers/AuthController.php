<?php
namespace App\Controllers;

use App\Models\User;
use Exception;

class AuthController extends BaseController
{
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al dashboard correspondiente
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard($_SESSION['user']['role']);
        }
        
        $this->render('auth/login', [
            'title' => 'Iniciar Sesión',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    public function login()
    {
        // Validar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // Validar CSRF token
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->render('auth/login', [
                'error' => 'Token de seguridad inválido',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        
        // Validaciones
        if (empty($email) || empty($password)) {
            $this->render('auth/login', [
                'error' => 'Todos los campos son requeridos',
                'email' => $email,
                'csrf_token' => $this->generateCsrfToken()
            ]);
            return;
        }
        
        try {
            $user = User::findByEmail($email);
            
            if (!$user || !$user->verifyPassword($password)) {
                $this->render('auth/login', [
                    'error' => 'Credenciales incorrectas',
                    'email' => $email,
                    'csrf_token' => $this->generateCsrfToken()
                ]);
                return;
            }
            
            // Iniciar sesión de forma segura
            $this->startSecureSession();
            $_SESSION['user'] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ];
            session_regenerate_id(true);
            
            // Redirigir según rol
            $this->redirectToDashboard($user->role);
            
        } catch (Exception $e) {
            error_log('Error en login: ' . $e->getMessage());
            $this->render('auth/login', [
                'error' => 'Ocurrió un error al iniciar sesión',
                'csrf_token' => $this->generateCsrfToken()
            ]);
        }
    }

    public function showRegistrationForm()
    {
        if ($this->isAuthenticated()) {
            $this->redirectToDashboard($_SESSION['user']['role']);
        }
        
        $this->render('auth/register', [
            'title' => 'Registro de Usuario',
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    public function register()
    {
        // Validar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }

        // Validar CSRF token
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->render('auth/register', [
                'error' => 'Token de seguridad inválido',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            return;
        }

        // Reemplazo de FILTER_SANITIZE_STRING (obsoleto)
        $name = $_POST['name'] ?? '';
        $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $name = trim($name);
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validaciones mejoradas
        $errors = [];
        if (empty($name)) $errors[] = 'El nombre es requerido';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido';
        }
        if (empty($password)) $errors[] = 'La contraseña es requerida';
        if (strlen($password) < 8) $errors[] = 'La contraseña debe tener al menos 8 caracteres';
        if (!preg_match('/[A-Z]/', $password)) $errors[] = 'La contraseña debe contener al menos una mayúscula';
        if (!preg_match('/[0-9]/', $password)) $errors[] = 'La contraseña debe contener al menos un número';
        if ($password !== $confirm_password) $errors[] = 'Las contraseñas no coinciden';
        
        if (!empty($errors)) {
            $this->render('auth/register', [
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
                'csrf_token' => $this->generateCsrfToken()
            ]);
            return;
        }
        
        try {
            // Verificar si el email ya existe
            if (User::findByEmail($email)) {
                $this->render('auth/register', [
                    'errors' => ['El email ya está registrado'],
                    'name' => $name,
                    'email' => $email,
                    'csrf_token' => $this->generateCsrfToken()
                ]);
                return;
            }
            
            // Crear y guardar usuario
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->setPassword($password);
            $user->role = 'user';
            
            if (!$user->save()) {
                throw new Exception('Error al guardar el usuario');
            }
            
            // Autenticar y redirigir
            $this->startSecureSession();
            $_SESSION['user'] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ];
            session_regenerate_id(true);
            $this->redirectToDashboard($user->role);
            
        } catch (Exception $e) {
            error_log('Error en registro: ' . $e->getMessage());
            $this->render('auth/register', [
                'errors' => ['Ocurrió un error durante el registro'],
                'name' => $name,
                'email' => $email,
                'csrf_token' => $this->generateCsrfToken()
            ]);
        }
    }

    public function logout()
    {
        // Destruir sesión completamente
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        
        session_destroy();
        $this->redirect('/login');
    }

    protected function isAuthenticated(): bool
    {
        $this->startSecureSession();
        return !empty($_SESSION['user']);
    }

    protected function redirectToDashboard(string $role)
    {
        $routes = [
            'admin' => '/admin/dashboard',
            'librarian' => '/librarian/dashboard',
            'user' => '/dashboard'
        ];
        
        $this->redirect($routes[$role] ?? '/dashboard');
    }

    protected function startSecureSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400, // 1 día
                'cookie_secure' => isset($_SERVER['HTTPS']), // Solo HTTPS en producción
                'cookie_httponly' => true,
                'use_strict_mode' => true,
                'sid_length' => 128,
                'sid_bits_per_character' => 6
            ]);
        }
    }

    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
}