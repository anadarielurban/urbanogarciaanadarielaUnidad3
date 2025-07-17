<?php
namespace App\Controllers;

class DashboardController {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function index() {
        // Verificar rol de usuario si es necesario
        if ($_SESSION['user']['role'] === 'admin') {
            $this->showAdminDashboard();
        } else {
            $this->showUserDashboard();
        }
    }

    private function showAdminDashboard() {
        // Lógica para el dashboard de administrador
        $stmt = $this->db->query("SELECT COUNT(*) as total_users FROM users");
        $totalUsers = $stmt->fetch()['total_users'];

        require __DIR__.'/../../views/admin/dashboard.php';
    }

    private function showUserDashboard() {
        // Lógica para el dashboard de usuario normal
        $userId = $_SESSION['user']['id'];
        $stmt = $this->db->prepare("SELECT * FROM books WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $books = $stmt->fetchAll();

        require __DIR__.'/../../views/user/dashboard.php';
    }
}