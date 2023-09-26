<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class ProductType
{
  /**
   * Função/Método responsável por listar todos os tipos de produtos da tabela product_type.
   * 
   * @return json $response - com os dados dos tipos de produtos e status de sucesso ou erro.
   */
  public static function listProductTypes()
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("SELECT * FROM product_type");
      $stmt->execute();

      $productTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Verificar se há tipos de produtos encontrados
      if ($productTypes) {
        // Construir a resposta JSON
        $response = [
          "status" => "success",
          "data" => $productTypes,
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum tipo de produto for encontrado, retornar uma mensagem de erro
        $response = [
          "status" => "error",
          "message" => "Nenhum tipo de produto encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro na listagem de tipos de produtos: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }

  /**
   * Função/Método responsável por criar um novo tipo de produto com base nos dados fornecidos.
   * 
   * @param array $request Os dados do tipo de produto a serem inseridos.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function createProductType($request)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("INSERT INTO product_type (name, tax, created_at) VALUES (:name, :tax, NOW())");

      $stmt->bindParam(":name", $request['name']);
      $stmt->bindParam(":tax", $request['tax']);

      $stmt->execute();

      $newProductTypeId = $pdo->lastInsertId();

      $response = [
        "status" => "success",
        "message" => "Tipo de produto inserido com sucesso.",
        "product_type_id" => $newProductTypeId
      ];

      header("Content-Type: application/json; charset=UTF-8");
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na inserção do tipo de produto: " . $e->getMessage());
    }
  }

  /**
   * Função/Método responsável por atualizar um tipo de produto com base nos dados fornecidos.
   * 
   * @param array $request Os dados do tipo de produto a serem atualizados.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function updateProductType($request)
  {
    try {
      // Verifique se o ID do tipo de produto está presente no pedido
      if (!isset($request['id'])) {
        $response = [
          "status" => "error",
          "message" => "O ID do tipo de produto é obrigatório para atualização.",
        ];

        http_response_code(400); // Bad Request
        echo json_encode($response);
        return;
      }

      $id = $request['id'];
      $name = $request['name'] ?? null;
      $tax = $request['tax'] ?? null;

      $pdo = Connection::getConnection();

      // Construa a consulta SQL de atualização
      $sql = "UPDATE product_type SET updated_at = NOW()";

      // Verifique e adicione os campos a serem atualizados
      $params = [];

      if (!empty($name)) {
        $sql .= ", name = :name";
        $params[':name'] = $name;
      }

      if (!empty($tax)) {
        $sql .= ", tax = :tax";
        $params[':tax'] = $tax;
      }

      $sql .= " WHERE id = :id";
      $params[':id'] = $id;

      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);

      // Verifique se algum registro foi alterado
      if ($stmt->rowCount() > 0) {
        // Se pelo menos um registro foi atualizado, significa que o tipo de produto foi atualizado
        $response = [
          "status" => "success",
          "message" => "Tipo de produto atualizado com sucesso.",
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum registro foi atualizado, significa que o tipo de produto não foi encontrado
        $response = [
          "status" => "error",
          "message" => "Tipo de produto não encontrado ou nenhum campo para atualizar especificado.",
        ];

        http_response_code(404); // Não encontrado
        echo json_encode($response);
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro ao atualizar o tipo de produto: " . $e->getMessage(),
      ];

      http_response_code(500); // Erro interno do servidor
      echo json_encode($response);
    }
  }


  /**
   * Função/Método responsável por deletar(softDelete) um tipo de produto.
   * 
   * @param int $id O ID do tipo de produto a ser deletado.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function deleteProductType($id)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("UPDATE product_type SET deleted_at = NOW() WHERE id = :id");
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();

      // Verificar se algum registro foi alterado
      if ($stmt->rowCount() > 0) {
        // Se pelo menos um registro foi atualizado, significa que o tipo de produto foi "deletado"
        $response = [
          "status" => "success",
          "message" => "Tipo de produto deletado com sucesso.",
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum registro foi atualizado, significa que o tipo de produto não foi encontrado
        $response = [
          "status" => "error",
          "message" => "Tipo de produto não encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro ao deletar o tipo de produto: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }
}
