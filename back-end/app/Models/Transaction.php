<?php

namespace App\Models;

use App\Database\Connection;
use App\Models\Stock;
use PDO;
use PDOException;

class Transaction
{
  /**
   * Função/Método responsável por criar uma nova transação de compra com base nos dados fornecidos.
   * 
   * @param array $request Os dados da transação de compra a serem inseridos.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function purchaseTransaction($request)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("INSERT INTO transactions (transaction_type, supplier_name, value_without_tax, total_tax, product_id, amount, total_value, created_at) 
                    VALUES (:transaction_type, :supplier_name, :value_without_tax, :total_tax, :product_id, :amount, :total_value, NOW())");

      $stmt->bindParam(":transaction_type", $request['transaction_type']);
      $stmt->bindParam(":supplier_name", $request['supplier_name']);
      $stmt->bindParam(":value_without_tax", $request['value_without_tax']);
      $stmt->bindParam(":total_tax", $request['total_tax']);
      $stmt->bindParam(":product_id", $request['product_id']);
      $stmt->bindParam(":amount", $request['amount']);
      $stmt->bindParam(":total_value", $request['total_value']);

      $stmt->execute();

      $newTransaction = $pdo->lastInsertId();

      $response = [
        "status" => "success",
        "message" => "Transação inserida com sucesso.",
        "transaction_id" => $newTransaction
      ];

      $request['transaction_id'] = $newTransaction;

      Stock::insertProduct($request);

      header("Content-Type: application/json; charset=UTF-8");
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na inserção da transação: " . $e->getMessage());
    }
  }

  /**
   * Função/Método responsável por criar uma nova transação de venda com base nos dados fornecidos.
   * 
   * @param array $request Os dados da transação de venda a serem inseridos.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function salesTransaction($request)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("INSERT INTO transactions (transaction_type, customer_name, product_id, amount, total_value, created_at) 
                    VALUES (:transaction_type, :customer_name, :product_id, :amount, :total_value, NOW())");

      $stmt->bindParam(":transaction_type", $request['transaction_type']);
      $stmt->bindParam(":customer_name", $request['customer_name']);
      $stmt->bindParam(":product_id", $request['product_id']);
      $stmt->bindParam(":amount", $request['amount']);
      $stmt->bindParam(":total_value", $request['total_value']);

      $stmt->execute();

      $newTransaction = $pdo->lastInsertId();

      $response = [
        "status" => "success",
        "message" => "Transação inserida com sucesso.",
        "transaction_id" => $newTransaction
      ];

      Stock::updateProduct($request);

      header("Content-Type: application/json; charset=UTF-8");
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na atualização da transação: " . $e->getMessage());
    }
  }

  /**
   * Função/Método responsável por listar todas as transações da tabela transactions.
   * 
   * @param
   * @return json $response - com os dados das transações e status de sucesso ou erro.
   */
  public static function listTransaction()
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("SELECT transactions.*, product.name AS product_name
      FROM transactions
      JOIN product ON transactions.product_id = product.id");
      $stmt->execute();

      $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Verificar se há transações encontradas
      if ($transactions) {
        // Construir a resposta JSON
        $response = [
          "status" => "success",
          "data" => $transactions,
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhuma transação for encontrada, retornar uma mensagem de erro
        $response = [
          "status" => "error",
          "message" => "Nenhuma transação encontrada.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro na listagem de transações: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }
}
