<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    public static function findByEmail($email)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchObject(static::class);
    }

    // Método para guardar el usuario
    public function save()
    {
        $db = static::getDB();
        
        if ($this->id) {
            // Actualizar
        } else {
            // Insertar
            $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
            $stmt->bindValue(':name', $this->name, \PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, \PDO::PARAM_STR);
            $stmt->bindValue(':password', $this->password, \PDO::PARAM_STR);
            $stmt->execute();
            
            $this->id = $db->lastInsertId();
        }
    }
}