<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Validations;

class ValidationsTest extends TestCase
{
  public function test_check_required_fields_success()
  {
    // Campos que devem ser enviado preenchidos no $request
    $request = [
      'transaction_type' => 'purchase',
      'supplier_name' => 'Fornecedor XYZ',
      'value_without_tax' => 1000,
      'total_tax' => 100,
      'product_id' => 1,
      'amount' => 10,
      'total_value' => 1100
    ];

    // Campos obrigatórios
    $requiredFields = [
      'transaction_type' => 'Tipo de transação',
      'supplier_name' => 'Nome do fornecedor',
      'value_without_tax' => 'Valor sem taxa',
      'total_tax' => 'Total de taxas',
      'product_id' => 'ID do produto',
      'amount' => 'Quantidade',
      'total_value' => 'Valor total'
    ];

    // Caso tenha algum campo que seja obrigatório vazio o $response volta uma str
    $response = Validations::checkRequiredFields($request, $requiredFields);

    $this->assertEmpty($response);
  }

  public function test_check_required_missing_fields()
  {
    // Campos que devem ser enviado preenchidos no $request
    $request = [
      'transaction_type' => 'purchase',
      'supplier_name' => 'Fornecedor XYZ',
      'value_without_tax' => 1000,
      'total_tax' => 100,
      'product_id' => 1,
      // 'amount' => 10,
      // 'total_value' => 1100
    ];

    // Campos obrigatórios
    $requiredFields = [
      'transaction_type' => 'Tipo de transação',
      'supplier_name' => 'Nome do fornecedor',
      'value_without_tax' => 'Valor sem taxa',
      'total_tax' => 'Total de taxas',
      'product_id' => 'ID do produto',
      'amount' => 'Quantidade',
      'total_value' => 'Valor total'
    ];

    // Caso tenha algum campo que seja obrigatório vazio o $response volta uma str
    $response = Validations::checkRequiredFields($request, $requiredFields);

    $this->assertNotEmpty($response);
  }
}
