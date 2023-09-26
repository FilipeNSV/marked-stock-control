<?php
/**
 * Nesse arquivo é onde todas as rotas API são colocadas, juntamente com o Controller e o método a serem chamados
 * Exemplo: "rota-exemplo" => "ExemploController@método"
 */
$routes = [
    "GET" => [
        "products-list" => "ProductController@listProducts",
        "product-get" => "ProductController@getProduct",
        "product-delete" => "ProductController@deleteProduct",
        "products-stock" => "ProductController@stockList",

        "product-types-list" => "ProductController@listProductTypes",
        "product-type-delete" => "ProductController@deleteProductType",

        "transaction-list" => "TransactionController@listTransaction",
    ],
    "POST" => [
        "product-create" => "ProductController@createProduct",
        "product-update" => "ProductController@updateProduct",

        "product-type-create" => "ProductController@createProductType",

        "transaction-purchase" => "TransactionController@purchaseTransaction",
        "transaction-sale" => "TransactionController@salesTransaction",
    ]
];
