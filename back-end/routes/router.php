<?php

use App\Http\Middleware\HeaderApiMiddleware;
use App\Http\Middleware\ProtectedRoute;

require_once __DIR__ . '/../vendor/autoload.php';

$headerMiddleware = new HeaderApiMiddleware;
$headerMiddleware->handle();

// Obtém a URL(URI) da solicitação
$url = $_SERVER['REQUEST_URI'];

// Divide a URL(URI) em partes
$urlParts = explode('/', $url);

if (isset($urlParts[1]) && $urlParts[1] === 'api') {

  if (isset($urlParts[2]) && $urlParts[2] != '') {
    require __DIR__ . '/../routes/api.php';
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "Nenhuma rota API foi requisitada!"));
    return;
  }
} else {
  http_response_code(404);
  echo json_encode(array("message" => "Apenas rotas API (/api) estão disponíveis nessa aplicação!"));
  return;
}

$method = $_SERVER['REQUEST_METHOD'];

if (!array_key_exists($method, $routes)) {
  http_response_code(405);
  echo json_encode(array("status" => 405, "message" => "Método não permitido"));
  return;
}

if (!array_key_exists($urlParts[2], $routes[$method])) {
  http_response_code(404);
  echo json_encode(array("status" => 404, "message" => "Rota não encontrada!"));
  return;
}

// Faz um foreach de todas as rotas que estão dentro do array do Método(HTTP) solicitado
foreach ($routes[$method] as $route => $handler) {
  if ($route == $urlParts[2]) {

    // Quando acha a rota, pega o nome do Controller e o Método
    list($controllerName, $action) = explode('@', $handler);

    // Verifica se a rota é protegida
    if(in_array($route, $protectedRoutes)) {
      $protectedRoute = new ProtectedRoute;
      $protectedRoute->authenticateJWT();
    }

    $controllerPath = "App\Http\Controllers\\" . $controllerName;

    if ($method == 'GET') {

      isset($urlParts[3]) ? $data = $urlParts[3] : $data = null;
      $instance = new $controllerPath;
      $instance->$action($data);
      break;
    } elseif ($method == 'POST') {

      $instance = new $controllerPath;
      $data = (array) json_decode(file_get_contents("php://input"), true);

      if (empty($data)) {
        $data = $_POST;
      }
      $instance->$action($data);
      break;
    }
  }
}
