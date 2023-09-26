<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Stock;

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
      echo json_encode(array("status" => 400, "message" => "É necessário passar o id do produto. Ex.: /product-get/1"));
      return;
    }

    return Product::getProduct($id);
  }

  public function createProduct($request)
  {
    return Product::createProduct($request);
  }

  public function updateProduct($request)
  {
    return Product::updateProduct($request);
  }

  public function deleteProduct($id = null)
  {
    if ($id == null) {
      http_response_code(400);
      echo json_encode(array("status" => 400, "message" => "É necessário passar o id do produto. Ex.: /product-delete/1"));
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
    return ProductType::createProductType($request);
  }

  public function deleteProductType($id = null)
  {
    if ($id == null) {
      http_response_code(400);
      echo json_encode(array("status" => 400, "message" => "É necessário passar o id do produto. Ex.: /product-delete/1"));
      return;
    }
    return ProductType::deleteProductType($id);
  }

  public function stockList()
  {
    return Stock::stockList();
  }
}
