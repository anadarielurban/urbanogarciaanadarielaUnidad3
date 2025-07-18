<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = 'users';

    public static function findByEmail(string $email): ?self
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $stmt->fetch() ?: null;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function save(): bool
    {
        $db = static::getDB();
        
        if (!empty($this->id)) {
            $stmt = $db->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        }
        
        $stmt->bindValue(':name', $this->name ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password ?? '', PDO::PARAM_STR);
        
        $result = $stmt->execute();
        
        if (empty($this->id) && $result) {
            $this->id = $db->lastInsertId();
        }
        
        return $result;
    }
}