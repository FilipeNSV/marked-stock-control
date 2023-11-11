<?php

namespace Tests\Unit;

use App\Http\Controllers\TransactionController;
use PHPUnit\Framework\TestCase;

class TransactionControllerTest extends TestCase
{
  /**
   * Testa uma transação de compra.
   */
  public function test_purchase_transaction()
  {
    // Cria uma instância do controlador
    $controller = new TransactionController();

    // Dados simulados da solicitação
    $request = [
      'transaction_type' => 'purchase',
      'supplier_name' => 'Fornecedor XYZ',
      'value_without_tax' => 1000,
      'total_tax' => 100,
      'product_id' => 1,
      'amount' => 10,
      'total_value' => 1100
    ];

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->purchaseTransaction($request);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK)
    $this->assertEquals(200, $httpStatusCode);
  }

  /**
   * Testa uma transação de venda.
   */
  public function test_sales_transaction()
  {
    // Cria uma instância do controlador
    $controller = new TransactionController();

    // Dados simulados da solicitação
    $request = [
      'transaction_type' => 'sale',
      'customer_name' => 'Cliente ABC',
      'product_id' => 1,
      'amount' => 100,
      'total_value' => 1000,
    ];

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->salesTransaction($request);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK)
    $this->assertEquals(200, $httpStatusCode);
  }

  /**
   * Testa a listagem de transações.
   */
  public function test_list_transaction()
  {
    // Cria uma instância do controlador
    $controller = new TransactionController();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->listTransaction();
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK) ou 404 (Not Found)
    $this->assertContains($httpStatusCode, [200, 404]);
  }
}
