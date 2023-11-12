<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
  /**
   * Testa a listagem de usuários.
   */
  public function test_list_users()
  {
    // Cria uma instância do controlador
    $controller = new UserController();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->listUsers();
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK) ou 404 (Not Found)
    $this->assertContains($httpStatusCode, [200, 404]);
  }

  /**
   * Testa a busca por um usuário específico.
   */
  public function test_get_user()
  {
    // Cria uma instância do controlador
    $controller = new UserController();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->getUser(1);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK) ou 404 (Not Found)
    $this->assertContains($httpStatusCode, [200, 404]);
  }

  /**
   * Testa a criação de um novo usuário.
   */
  public function test_create_user()
  {
    // Cria uma instância do controlador
    $controller = new UserController();

    $password = password_hash('abcd', PASSWORD_DEFAULT);

    $request = [
      'name' => 'Usuario Teste - ' . date('Y-m-d H:i:s'),
      'email' => 'user_test@email.com',
      'password' => $password
    ];

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->createUser($request);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK)
    $this->assertEquals(200, $httpStatusCode);
  }
}
