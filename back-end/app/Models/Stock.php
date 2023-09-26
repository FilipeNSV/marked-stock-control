<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class Stock
{
  /**
   * Insere produto(s) no estoque com base na quantidade fornecida.
   *
   * @param array $request Os dados do produto e quantidade a serem inseridos.
   * @return void
   */
  public static function insertProduct($request)
  {
    try {
      $pdo = Connection::getConnection();

      // Certifique-se de que $request['amount'] seja um número inteiro positivo
      $amount = intval($request['amount']);

      $value_without_tax = $request['value_without_tax'] / $amount;
      $total_tax = $request['total_tax'] / $amount;
      $total_value = $request['total_value'] / $amount;

      for ($i = 0; $i < $amount; $i++) {
        $stmt = $pdo->prepare("INSERT INTO stock (transaction_id, product_id, value_without_tax, total_tax, total_value, status, created_at) VALUES (:transaction_id, :product_id, :value_without_tax, :total_tax, :total_value, 'available', NOW())");

        $stmt->bindParam(":transaction_id", $request['transaction_id']);
        $stmt->bindParam(":product_id", $request['product_id']);
        $stmt->bindParam(":value_without_tax", $value_without_tax);
        $stmt->bindParam(":total_tax", $total_tax);
        $stmt->bindParam(":total_value", $total_value);

        $stmt->execute();
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na inserção do produto: " . $e->getMessage());
    }
  }

  /**
   * Atualiza produto(s) no estoque com base na quantidade fornecida.
   *
   * @param array $request Os dados do produto e quantidade a serem atualizados.
   * @return void
   */
  public static function updateProduct($request)
  {
    try {
      $pdo = Connection::getConnection();

      // Certifique-se de que $request['amount'] seja um número inteiro positivo
      $amount = intval($request['amount']);

      // Prepare a consulta SQL para selecionar os IDs dos registros a serem atualizados
      $stmtSelect = $pdo->prepare("SELECT id FROM stock WHERE product_id = :product_id ORDER BY id ASC");
      $stmtSelect->bindParam(":product_id", $request['product_id'], PDO::PARAM_INT);
      $stmtSelect->execute();

      // Obtém os IDs dos registros a serem atualizados
      $ids = $stmtSelect->fetchAll(PDO::FETCH_COLUMN);

      // Limita a quantidade de IDs aos primeiros $amount IDs
      $idsToUpdate = array_slice($ids, 0, $amount);

      // Itera sobre os IDs e atualiza os registros individualmente
      foreach ($idsToUpdate as $id) {
        // Prepare a consulta SQL de atualização para um registro específico
        $stmtUpdate = $pdo->prepare("UPDATE stock SET deleted_at = NOW(), status = 'unavailable' WHERE id = :id");
        $stmtUpdate->bindParam(":id", $id, PDO::PARAM_INT);
        $stmtUpdate->execute();
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na atualização do produto: " . $e->getMessage());
    }
  }

  /**
   * Função/Método responsável por listar todas os produtos da tabela stock.
   * 
   * @param
   * @return json $response - com os dados dos produtos e status de sucesso ou erro.
   */
  public static function stockList()
  {
    $pdo = Connection::getConnection();

    // $stmt = $pdo->prepare("SELECT * FROM stock");
    $stmt = $pdo->prepare("SELECT stock.*, product.name AS product_name
      FROM stock
      JOIN product ON stock.product_id = product.id");
    $stmt->execute();

    $stockList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar se há transações encontradas
    if ($stockList) {
      // Construir a resposta JSON
      $response = [
        "status" => "success",
        "data" => $stockList,
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
  }
}
