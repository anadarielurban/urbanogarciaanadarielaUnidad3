<?php
namespace App\Models;

use App\Core\Model;
use PDO;
use Exception;
use PDOException;

class User extends Model
{
    protected $table = 'users';

    public $errors = [];

    public static function findByEmail($email)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchObject(static::class);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function save()
    {
        // Verificar que los campos requeridos estén establecidos
        if (empty($this->name)) {
            throw new Exception('El nombre es requerido');
        }
        
        if (empty($this->email)) {
            throw new Exception('El email es requerido');
        }
        
        if (empty($this->password)) {
            throw new Exception('La contraseña no ha sido establecida');
        }

        $db = static::getDB();
        
        try {
            if ($this->id) {
                // Actualizar usuario existente
                $stmt = $db->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
                $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            } else {
                // Insertar nuevo usuario
                $stmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
                $this->role = $this->role ?? 'user'; // Establecer rol por defecto
            }
            
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
            
            if (!isset($this->id)) {
                $stmt->bindValue(':role', $this->role, PDO::PARAM_STR);
            }
            
            $result = $stmt->execute();
            
            if (!$this->id) {
                $this->id = $db->lastInsertId();
            }
            
            return $result;
            
        } catch (PDOException $e) {
            // Manejar error de duplicado de email
            if ($e->getCode() == 23000) {
                $this->errors['email'] = 'El email ya está registrado';
                return false;
            }
            
            // Registrar el error para depuración
            error_log('Error en User::save(): ' . $e->getMessage());
            throw $e; // Relanzar otras excepciones
        }
    }
}