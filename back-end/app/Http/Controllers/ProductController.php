<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Stock;
use App\Models\Validations;

class ProductController
{
  public function listProducts()
  {
    return Product::listProducts();
  }

  public function getProduct($id = null)
  {
    if ($id == null) {
      http_response_code(400);
      echo json_encode(array("status" => 'error', "message" => "É necessário passar o id do produto. Ex.: /product-get/1"));
      return;
    }

    return Product::getProduct($id);
  }

  public function createProduct($request)
  {
    // Validação dos campos obrigatórios
    $requiredFields = [
      'name' => 'Nome',
      'description' => 'Descrição',
      'product_type_id' => 'Tipo do Produto',
      'value' => 'Valor'
    ];

    $missingFields = Validations::checkRequiredFields($request, $requiredFields);

    if (!empty($missingFields)) {
      http_response_code(400);
      echo json_encode(["status" => 'error', "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)]);
      return;
    }

    return Product::createProduct($request);
  }

  public function updateProduct($request)
  {
    // Verifica se o ID do produto está presente na requisição
    if (!isset($request['id']) || is_null($request['id'])) {
      http_response_code(400);
      echo json_encode(array("status" => 'error', "message" => "O ID do produto é obrigatório para atualização!"));
      return;
    }

    return Product::updateProduct($request);
  }

  public function deleteProduct($id = null)
  {
    if ($id == null) {
      http_response_code(400);
      echo json_encode(array("status" => 'error', "message" => "É necessário passar o id do produto. Ex.: /product-delete/1"));
      return;
    }

    return Product::deleteProduct($id);
  }

  public function listProductTypes()
  {
    return ProductType::listProductTypes();
  }

  public function createProductType($request)
  {
    // Validação dos campos obrigatórios
    $requiredFields = [
      'name' => 'Nome',
      'tax' => 'Taxa'
    ];

    $missingFields = Validations::checkRequiredFields($request, $requiredFields);

    if (!empty($missingFields)) {
      http_response_code(400);
      echo json_encode(["status" => 'error', "message" => "Preencha o(s) Campo(s) obrigatório(s): " . implode(", ", $missingFields)]);
      return;
    }

    return ProductType::createProductType($request);
  }

  public function deleteProductType($id = null)
  {
    if ($id == null) {
      http_response_code(400);
      echo json_encode(array("status" => 'error', "message" => "É necessário passar o id do produto. Ex.: /product-delete/1"));
      return;
    }
    return ProductType::deleteProductType($id);
  }

  public function stockList()
  {
    return Stock::stockList();
  }
}
