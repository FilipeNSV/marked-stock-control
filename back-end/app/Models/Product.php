<?php

namespace App\Models;

use App\Database\Connection;
use PDO;
use PDOException;

class Product
{
  /**
   * Função/Método responsável por listar todos os produtos da tabela product.
   * 
   * @param
   * @return json $response - com os dados dos produtos e status de sucesso ou erro.
   */
  public static function listProducts()
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("SELECT product.id, product.name, product.product_type_id, product.description, product.value, product.created_at, 
                      product_type.name AS product_type_name, product_type.tax AS tax
                      FROM product JOIN product_type ON product.product_type_id = product_type.id
                      WHERE product.deleted_at IS NULL");

      $stmt->execute();

      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Verificar se há produtos encontrados
      if ($products) {
        // Construir a resposta JSON
        $response = [
          "status" => "success",
          "data" => $products,
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum produto for encontrado, retornar uma mensagem de erro
        $response = [
          "status" => "error",
          "message" => "Nenhum produto encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro na listagem de produtos: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }

  /**
   * Função/Método responsável por cria um novo produto com base nos dados fornecidos.
   * 
   * @param int $id O ID do produto a ser obtido.
   * @return json $response - com os dados do produto e status de sucesso ou erro.
   */
  public static function getProduct($id)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();

      $product = $stmt->fetch(PDO::FETCH_ASSOC);

      // Verificar se o produto foi encontrado
      if ($product) {
        $response = [
          "status" => "success",
          "data" => $product,
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se o produto não for encontrado, retornar uma mensagem de erro
        $response = [
          "status" => "error",
          "message" => "Produto não encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro ao buscar o produto: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }

  /**
   * Função/Método responsável por cria um novo produto com base nos dados fornecidos.
   * 
   * @param array $request Os dados do produto a serem inseridos.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function createProduct($request)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("INSERT INTO product (name, description, product_type_id, value, created_at) VALUES (:name, :description, :product_type_id, :value, NOW())");

      $stmt->bindParam(":name", $request['name']);
      $stmt->bindParam(":description", $request['description']);
      $stmt->bindParam(":product_type_id", $request['product_type_id']);
      $stmt->bindParam(":value", $request['value']);

      $stmt->execute();

      $newProductId = $pdo->lastInsertId();

      $response = [
        "status" => "success",
        "message" => "Produto inserido com sucesso.",
        "product_id" => $newProductId
      ];

      header("Content-Type: application/json; charset=UTF-8");
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      die("Erro na inserção do produto: " . $e->getMessage());
    }
  }

  /**
   * Função/Método responsável por atualizar um produto com base nos dados fornecidos.
   * 
   * @param array $request Os dados do produto a serem atualizados.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function updateProduct($request)
  {
    try {
      // Verifique se o ID do produto está presente no pedido
      if (!isset($request['id'])) {
        $response = [
          "status" => "error",
          "message" => "O ID do produto é obrigatório para atualização.",
        ];

        http_response_code(400); // Bad Request
        echo json_encode($response);
        return;
      }

      $id = $request['id'];
      $name = $request['name'] ?? null;
      $description = $request['description'] ?? null;
      $product_type_id = $request['product_type_id'] ?? null;
      $value = $request['value'] ?? null;

      $pdo = Connection::getConnection();

      // Construa a consulta SQL de atualização
      $sql = "UPDATE product SET updated_at = NOW()";

      // Verifique e adicione os campos a serem atualizados
      $params = [];

      if (!empty($name)) {
        $sql .= ", name = :name";
        $params[':name'] = $name;
      }

      if (!empty($description)) {
        $sql .= ", description = :description";
        $params[':description'] = $description;
      }

      if (!empty($product_type_id)) {
        $sql .= ", product_type_id = :product_type_id";
        $params[':product_type_id'] = $product_type_id;
      }

      if (!empty($value)) {
        $sql .= ", value = :value";
        $params[':value'] = $value;
      }

      $sql .= " WHERE id = :id";
      $params[':id'] = $id;

      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);

      // Verifique se algum registro foi alterado
      if ($stmt->rowCount() > 0) {
        // Se pelo menos um registro foi atualizado, significa que o produto foi atualizado
        $response = [
          "status" => "success",
          "message" => "Produto atualizado com sucesso.",
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum registro foi atualizado, significa que o produto não foi encontrado
        $response = [
          "status" => "error",
          "message" => "Produto não encontrado ou nenhum campo para atualizar especificado.",
        ];

        http_response_code(404); // Não encontrado
        echo json_encode($response);
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro ao atualizar o produto: " . $e->getMessage(),
      ];

      http_response_code(500); // Erro interno do servidor
      echo json_encode($response);
    }
  }

  /**
   * Função/Método responsável por deletar(softDelete) um produto.
   * 
   * @param int $id O ID do produto a ser deletado.
   * @return json $response - status e mensagem de sucesso ou erro.
   */
  public static function deleteProduct($id)
  {
    try {
      $pdo = Connection::getConnection();

      $stmt = $pdo->prepare("UPDATE product SET deleted_at = NOW() WHERE id = :id");
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->execute();

      // Verificar se algum registro foi alterado
      if ($stmt->rowCount() > 0) {
        // Se pelo menos um registro foi atualizado, significa que o produto foi "deletado"
        $response = [
          "status" => "success",
          "message" => "Produto deletado com sucesso.",
        ];

        header("Content-Type: application/json");
        echo json_encode($response);
      } else {
        // Se nenhum registro foi atualizado, significa que o produto não foi encontrado
        $response = [
          "status" => "error",
          "message" => "Produto não encontrado.",
        ];

        http_response_code(404);
        echo json_encode($response);
      }
    } catch (\PDOException $e) {
      // Lidar com erros de banco de dados, se houver
      $response = [
        "status" => "error",
        "message" => "Erro ao deletar o produto: " . $e->getMessage(),
      ];

      http_response_code(500);
      echo json_encode($response);
    }
  }
}
