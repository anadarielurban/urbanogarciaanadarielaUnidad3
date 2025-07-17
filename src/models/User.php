<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role'];

    /**
     * Busca un usuario por email
     */
    public static function findByEmail(string $email): ?self
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        return $stmt->fetch() ?: null;
    }

    /**
     * Hashea y establece la contraseña del usuario
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this; // Permite encadenamiento de métodos
    }

    /**
     * Verifica si la contraseña es correcta
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Guarda o actualiza el usuario
     */
    public function save(): bool
    {
        $db = static::getDB();
        
        if (!empty($this->id)) {
            // Actualizar
            $stmt = $db->prepare('
                UPDATE users SET 
                    name = :name, 
                    email = :email, 
                    password = :password,
                    role = :role
                WHERE id = :id
            ');
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
        } else {
            // Insertar
            $stmt = $db->prepare('
                INSERT INTO users 
                    (name, email, password, role) 
                VALUES 
                    (:name, :email, :password, :role)
            ');
        }
        
        $stmt->bindValue(':name', $this->name ?? '', \PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email ?? '', \PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password ?? '', \PDO::PARAM_STR);
        $stmt->bindValue(':role', $this->role ?? 'user', \PDO::PARAM_STR);
        
        return $stmt->execute();
    }
}