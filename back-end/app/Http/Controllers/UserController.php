<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Validations;

class UserController
{
  public function listUsers()
  {
    return User::listUsers();
  }

  public function getUser($id = null)
  {
    if (is_null($id) || empty($id)) {
      http_response_code(400);
      echo json_encode(array("status" => 'error', "message" => "É necessário passar o id do produto. Ex.: /user-get/1"));
      return;
    }

    return User::getUser($id);
  }

  public function createUser($request)
  {
    // Validação dos campos obrigatórios
    $requiredFields = [
      'name' => 'Nome',
      'email' => 'E-mail',
      'password' => 'Senha'
    ];

    $missingFields = Validations::checkRequiredFields($request, $requiredFields);

    if (!empty($missingFields)) {
      http_response_code(400);
      echo json_encode(["status" => 'error', "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)]);
      return;
    }

    return User::createUser($request);
  }
}
