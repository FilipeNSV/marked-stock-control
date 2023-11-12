<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class User
{
  /**
   * Função/Método responsável por listar todos os usuários da tabela users.
   * 
   * @param
   * @return json $response - com os dados dos usuários e status de sucesso ou erro.
   */
  public static function listUsers()
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("SELECT name, email, created_at FROM users WHERE deleted_at IS NULL");

      $stmt->execute();

      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Verificar se há usuários encontrados
      if ($users) {
        // Construir a resposta JSON
        $response = [
          "status" => "success",
          "data" => $users,
        ];

        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum usuário for encontrado, retornar uma mensagem de erro
        $response = [
          "status" => "error",
          "message" => "Nenhum usuário encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro na listagem de usuários: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }

  /**
   * Função/Método responsável por buscar um usuário específico.
   * 
   * @param int $id O ID do usuário a ser obtido.
   * @return json $response - com os dados do usuário e status de sucesso ou erro.
   */
  public static function getUser($id)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Verificar se o usuário foi encontrado
      if ($user) {
        $response = [
          "status" => "success",
          "data" => $user,
        ];

        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se o usuário não for encontrado, retornar uma mensagem de erro
        $response = [
          "status" => "error",
          "message" => "Usuário não encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro ao buscar o usuário: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }

  /**
   * Função/Método responsável por cria um novo usuário com base nos dados fornecidos.
   * 
   * @param array $request Os dados do usuário a serem inseridos.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function createUser($request)
  {
    try {
      $pdo = Connection::getConnection();

      $hashedPassword = password_hash($request['password'], PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())");

      $stmt->bindParam(":name", $request['name']);
      $stmt->bindParam(":email", $request['email']);
      $stmt->bindParam(":password", $hashedPassword);

      $stmt->execute();

      $newUserId = $pdo->lastInsertId();

      $response = [
        "status" => "success",
        "message" => "usuário inserido com sucesso.",
        "user_id" => $newUserId
      ];

      http_response_code(200);
      header("Content-Type: application/json; charset=UTF-8");
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na inserção do usuário: " . $e->getMessage());
    }
  }
}
