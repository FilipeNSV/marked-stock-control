<?php

namespace App\Http\Controllers;

use App\Database\Connection;
use App\Models\Validations;
use PDO;
use Dotenv;
use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
$dotenv->load();

class AuthController
{
  public function login($request)
  {
    // Validação dos campos obrigatórios
    $requiredFields = [
      'email' => 'E-mail',
      'password' => 'Senha'
    ];

    $missingFields = Validations::checkRequiredFields($request, $requiredFields);

    if (!empty($missingFields)) {
      http_response_code(400);
      echo json_encode(["status" => 'error', "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)]);
      return;
    }

    $pdo = Connection::getConnection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $request['email'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário foi encontrado
    if ($user) {
      if (!password_verify($request['password'], $user['password'])) {
        
        $response = [
          "status" => "error",
          "message" => "E-mail e/ou Senha incorreta.",
        ];
  
        http_response_code(404);
        echo json_encode($response);
        return;
      } else {

        $payload = [
          "exp" => time() + 3600, // 3600 Segundos (1 Hora)
          "iat" => time(),
          "email" => $request['email']
        ];

        $tokenGenerator = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

        $response = ['token' => $tokenGenerator, 'name' => $user['name']];
  
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($response);
        return;
      }
      
    } else {
      // Se o usuário não for encontrado, retornar uma mensagem de erro
      $response = [
        "status" => "error",
        "message" => "Usuário não encontrado.",
      ];

      http_response_code(404);
      echo json_encode($response);
      return;
    }
  }
}
