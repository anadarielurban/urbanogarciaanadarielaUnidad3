<?php
namespace App\Core;

use PDO;
use PDOException;

class Model
{
    protected static $db;
    protected $table;
    protected $fillable = []; // Campos que se pueden asignar masivamente

    public function __construct()
    {
        $this->connectDB();
    }

    protected function connectDB()
    {
        if (!isset(self::$db)) {
            try {
                self::$db = new PDO(
                    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS']
                );
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Error de conexión: ' . $e->getMessage());
            }
        }
    }

    protected static function getDB()
    {
        (new static())->connectDB();
        return self::$db;
    }

    public function __get($name)
    {
        return $this->$name ?? null;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->fillable)) {
            $this->$name = $value;
        }
    }
}