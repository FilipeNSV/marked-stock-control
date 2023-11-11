<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Validations;

class TransactionController
{
  public function purchaseTransaction($request)
  {
    // Validação dos campos obrigatórios
    $requiredFields = [
      'transaction_type' => 'Tipo de transação',
      'supplier_name' => 'Nome do fornecedor',
      'value_without_tax' => 'Valor sem taxa',
      'total_tax' => 'Total de taxas',
      'product_id' => 'ID do produto',
      'amount' => 'Quantidade',
      'total_value' => 'Valor total'
    ];

    $missingFields = Validations::checkRequiredFields($request, $requiredFields);

    if (!empty($missingFields)) {
      http_response_code(400);
      echo json_encode(["status" => 'error', "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)]);
      return;
    }

    return Transaction::purchaseTransaction($request);
  }

  public function salesTransaction($request)
  {
    // Validação dos campos obrigatórios
    $requiredFields = [
      'transaction_type' => 'Tipo de transação',
      'customer_name' => 'Nome do cliente',
      'product_id' => 'ID do produto',
      'amount' => 'Quantidade',
      'total_value' => 'Valor total'
    ];

    $missingFields = Validations::checkRequiredFields($request, $requiredFields);

    if (!empty($missingFields)) {
      http_response_code(400);
      echo json_encode(["status" => 'error', "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)]);
      return;
    }

    return Transaction::salesTransaction($request);
  }

  public function listTransaction()
  {
    return Transaction::listTransaction();
  }
}
