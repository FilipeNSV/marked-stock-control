<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use PHPUnit\Framework\TestCase;

class ProductControllerTest extends TestCase
{
  /**
   * Testa a listagem de produtos.
   */
  public function test_list_products()
  {
    // Cria uma instância do controlador
    $controller = new ProductController();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->listProducts();
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK) ou 404 (Not Found)
    $this->assertContains($httpStatusCode, [200, 404]);
  }

  /**
   * Testa a listagem de tipos de produto.
   */
  public function test_list_products_type()
  {
    // Cria uma instância do controlador
    $controller = new ProductController();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->listProductTypes();
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK) ou 404 (Not Found)
    $this->assertContains($httpStatusCode, [200, 404]);
  }

  /**
   * Testa a busca por um produto específico.
   */
  public function test_get_product()
  {
    // Cria uma instância do controlador
    $controller = new ProductController();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->getProduct(1);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK) ou 404 (Not Found)
    $this->assertContains($httpStatusCode, [200, 404]);
  }

  /**
   * Testa a criação de tipo de produto e produto.
   */
  public function test_create_product_type_and_product()
  {
    // Cria uma instância do controlador
    $controller = new ProductController();

    $productTypeRequest = [
      'name' => 'Produto Tipo Teste - ' . date('Y-m-d H:i:s'),
      'tax' => 10
    ];

    ob_start();
    $controller->createProductType($productTypeRequest);
    $output = ob_get_clean();

    // Decodifica a resposta JSON para obter o ID do tipo de produto
    $responseData = json_decode($output, true);

    // Obtém o ID do tipo de produto recém-inserido
    $productTypeId = $responseData['product_type_id'];

    $productRequest = [
      'name' => 'Produto Teste' . date('Y-m-d H:i:s'),
      'description' => 'Melhor produto Teste do mercado',
      'product_type_id' => $productTypeId,
      'value' => 100
    ];
    
    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->createProduct($productRequest);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK)
    $this->assertEquals(200, $httpStatusCode);
  }

  /**
   * Testa a edição de um produto.
   */
  public function test_update_product()
  {
    // Cria uma instância do controlador
    $controller = new ProductController();

    $productTypeRequest = [
      'name' => 'Produto Tipo Teste - ' . date('Y-m-d H:i:s'),
      'tax' => 10
    ];

    ob_start();
    $controller->createProductType($productTypeRequest);
    $output1 = ob_get_clean();

    // Decodifica a resposta JSON para obter o ID do tipo de produto
    $responseType = json_decode($output1, true);

    // Obtém o ID do tipo de produto recém-inserido
    $productTypeId = $responseType['product_type_id'];

    $productRequest = [
      'name' => 'Produto Teste' . date('Y-m-d H:i:s'),
      'description' => 'Melhor produto Teste do mercado',
      'product_type_id' => $productTypeId,
      'value' => 100
    ];
    
    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->createProduct($productRequest);
    $output2 = ob_get_clean();

    $responseProduct = json_decode($output2, true);

    // Obtém o ID do tipo de produto recém-inserido
    $productId = $responseProduct['product_id'];

    $requestUpdate = [
      'id' => $productId,
      'description' => 'Produto Editado Teste' . date('Y-m-d H:i:s')
    ];

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->updateProduct($requestUpdate);
    $httpStatusCode = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK)
    $this->assertEquals(200, $httpStatusCode);
  }

  /**
   * Testa a exclusão de um de produto e um tipo produto.
   */
  public function test_delete_product_type_and_product()
  {
    // Cria uma instância do controlador
    $controller = new ProductController();

    $productTypeRequest = [
      'name' => 'Produto Tipo Teste - ' . date('Y-m-d H:i:s'),
      'tax' => 10
    ];

    ob_start();
    $controller->createProductType($productTypeRequest);
    $output1 = ob_get_clean();

    // Decodifica a resposta JSON para obter o ID do tipo de produto
    $responseType = json_decode($output1, true);

    // Obtém o ID do tipo de produto recém-inserido
    $productTypeId = $responseType['product_type_id'];

    $productRequest = [
      'name' => 'Produto Teste' . date('Y-m-d H:i:s'),
      'description' => 'Melhor produto Teste do mercado',
      'product_type_id' => $productTypeId,
      'value' => 100
    ];
    
    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->createProduct($productRequest);
    $output2 = ob_get_clean();

    $responseProduct = json_decode($output2, true);

    // Obtém o ID do tipo de produto recém-inserido
    $productId = $responseProduct['product_id'];

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->deleteProduct($productId);
    $httpStatusCodeProduct = http_response_code();
    ob_end_clean();

    // Captura o código de status HTTP retornado pela função
    ob_start();
    $controller->deleteProductType($productTypeId);
    $httpStatusCodeProductType = http_response_code();
    ob_end_clean();

    // Verifica se o código de status é 200 (OK)
    $this->assertEquals(200, $httpStatusCodeProduct);
    $this->assertEquals(200, $httpStatusCodeProductType);
  }
}