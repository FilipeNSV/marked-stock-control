<?php

namespace App\Models;

class Validations
{
  /**
   * Verifica se os campos obrigatórios estão presentes na requisição.
   *
   * @param array $request Dados da requisição.
   * @param array $requiredFields Campos obrigatórios associados a mensagens personalizadas.
   * @return array Lista de campos ausentes com mensagens personalizadas.
   */
  public static function checkRequiredFields($request, $requiredFields)
  {
    $missingFields = [];
    foreach ($requiredFields as $field => $message) {
      if (!isset($request[$field]) || is_null($request[$field]) || $request[$field]) {
        $missingFields[] = $message;
      }
    }
    return $missingFields;
  }
}
