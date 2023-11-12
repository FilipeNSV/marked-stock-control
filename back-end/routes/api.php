<?php
/**
 * Nesse arquivo é onde todas as rotas API são colocadas, juntamente com o Controller e o(s) método(s) a serem chamados.
 * Exemplo: "rota-exemplo" => "ExemploController@método"
 * 
 * Também inserimos as rotas que devem ser protegidas(token) no array $protectedRoutes
 */
$routes = [
    "GET" => [
        "user-list" => "UserController@listUsers",
        "user-get" => "UserController@getUser",
        "products-list" => "ProductController@listProducts",
        "product-get" => "ProductController@getProduct",
        "product-delete" => "ProductController@deleteProduct",
        "products-stock" => "ProductController@stockList",
        "product-types-list" => "ProductController@listProductTypes",
        "product-type-delete" => "ProductController@deleteProductType",
        "transaction-list" => "TransactionController@listTransaction",
    ],
    "POST" => [
        "user-create" => "UserController@createUser",
        "user-login" => "AuthController@login",

        "product-create" => "ProductController@createProduct",
        "product-update" => "ProductController@updateProduct",
        "product-type-create" => "ProductController@createProductType",
        "transaction-purchase" => "TransactionController@purchaseTransaction",
        "transaction-sale" => "TransactionController@salesTransaction",
    ]
];

// Rotas protegidas
$protectedRoutes = [
    "user-list",
    "user-get",
    "products-list",
    "product-get",
    "product-delete",
    "products-stock",
    "product-types-list",
    "product-type-delete",
    "transaction-list",
    "product-create",
    "product-update",
    "product-type-create",
    "transaction-purchase",
    "transaction-sale"
];
