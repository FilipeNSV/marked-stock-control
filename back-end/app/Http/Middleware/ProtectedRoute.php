<?php

namespace App\Http\Middleware;

use Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../");
$dotenv->load();

class ProtectedRoute
{
  public static function authenticateJWT()
  {
    // Verifica se o token está presente no cabeçalho da requisição
    $authorization = $_SERVER["HTTP_AUTHORIZATION"] ?? null;
    $token = str_replace('Bearer ', '', $authorization);

    if (!$token) {
      http_response_code(401);
      echo json_encode(['error' => 'Token não encontrado!']);
      exit;
    }

    $decoded = null;

    try {
      $key = new Key($_ENV['JWT_KEY'], 'HS256');
      $decoded = JWT::decode($token, $key);
    } catch (\Throwable $e) {
      if ($e->getMessage() == 'Expired token') {
        http_response_code(401);
        echo json_encode(['error' => 'Token Expirado!']);
        exit;
      }

      if ($e->getMessage() == 'Signature verification failed') {
        http_response_code(401);
        echo json_encode(['error' => 'Token Inválido!']);
        exit;
      }

      echo json_encode($e->getMessage());
      http_response_code(401);
      exit;
    }

    // Verifique se $decoded é um objeto
    if ($decoded == null && !is_object($decoded)) {
      http_response_code(401);
      echo json_encode(['error' => 'Token inválido!']);
      exit;
    }
  }
}
