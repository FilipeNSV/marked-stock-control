<?php

namespace App\Database;

use PDO;
use PDOException;
use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

class Connection
{
  public static function getConnection()
  {
    $dbconnection = $_ENV['DB_CONNECTION'];
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $dbname = $_ENV['DB_DATABASE'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];

    try {
      $pdo = new PDO("$dbconnection:host=$host;port=$port;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Conexão bem-sucedida!";
      return $pdo;
    } catch (PDOException $e) {
      die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
  }
}
