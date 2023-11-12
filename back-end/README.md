# Back-End da Aplicação de Controle de Estoque - Mercado

Este é o repositório do back-end da aplicação de Controle de Estoque, desenvolvido em PHP 8.1. Este back-end é projetado exclusivamente para lidar com rotas da API, oferecendo um gerenciamento de rotas simples e eficiente, com excelente desempenho. As rotas também tem autenticação para uma melhor segurança da aplicação. Ele se conecta ao banco de dados PostgreSQL e segue o padrão MVC, mantendo um código limpo e organizado.

## Tecnologias Utilizadas

Aplicação de Controle de Estoque para o mercado foi desenvolvida utilizando as seguintes tecnologias, frameworks e bibliotecas:

- **PHP 8.1:**
- **PostgreSQL:**

Além das tecnologias mencionadas acima, foi utilizado o padrão MVC para manter um código organizado e de fácil manutenção.

## Funcionalidades

O back-end é responsável pela conexão com o banco de dados e pelo processamento das informações das requisições, fornecendo ao front-end todas as funcionalidades e processamento de dados necessários.

### Controle de Estoque

O Controle de Estoque fornece um resumo das quantidades de produtos disponíveis com base nas transações de compra e venda registradas.

### CRUD de Produtos

- **Listagem de Produtos:** Visualize todos os produtos cadastrados, incluindo detalhes como nome, tipo e quantidade em estoque.
- **Cadastro de Produto:** Adicione novos produtos com informações como nome, descrição, preço e tipo.
- **Edição de Produto:** Atualize as informações de um produto existente.
- **Exclusão de Produto:** Realiza um soft delete (marca a coluna deleted_at).

### Tipos de Produto

- **Listagem de Tipos de Produto:** Veja uma lista de todos os tipos de produtos cadastrados.
- **Cadastro de Tipo de Produto:** Crie novos tipos de produtos para categorizar seus produtos.
- **Exclusão de Tipo de Produto:** Realiza um soft delete (marca a coluna deleted_at).

### Transações de Compra e Venda

- **Registro de Compra:** Registre a compra de produtos, especificando a quantidade, data e fornecedor.
- **Registro de Venda:** Registre a venda de produtos, especificando a quantidade, data e cliente.
- **Histórico de Transações:** Veja um histórico completo de todas as transações de compra e venda realizadas.

### Rotas da API

A seguir, estão listadas as rotas da API e seus respectivos controladores:

#### Rotas GET (Com autenticação)

- **GET /api/products-list:** Retorna a lista de produtos. Controlador: `ProductController@listProducts`.
- **GET /api/product-get:** Retorna detalhes de um produto específico. Controlador: `ProductController@getProduct`.
- **GET /api/product-delete:** Marca um produto como excluído (soft delete). Controlador: `ProductController@deleteProduct`.
- **GET /api/products-stock:** Retorna o estoque de produtos. Controlador: `ProductController@stockList`.
- **GET /api/product-types-list:** Retorna a lista de tipos de produtos. Controlador: `ProductController@listProductTypes`.
- **GET /api/product-type-delete:** Marca um tipo de produto como excluído (soft delete). Controlador: `ProductController@deleteProductType`.
- **GET /api/transaction-list:** Retorna a lista de transações. Controlador: `TransactionController@listTransaction`.

#### Rotas POST (Com autenticação)

- **POST /api/product-create:** Cria um novo produto. Controlador: `ProductController@createProduct`.
- **POST /api/product-update:** Atualiza as informações de um produto. Controlador: `ProductController@updateProduct`.
- **POST /api/product-type-create:** Cria um novo tipo de produto. Controlador: `ProductController@createProductType`.
- **POST /api/transaction-purchase:** Registra uma transação de compra. Controlador: `TransactionController@purchaseTransaction`.
- **POST /api/transaction-sale:** Registra uma transação de venda. Controlador: `TransactionController@salesTransaction`.

#### Rotas POST (Sem autenticação)
- **POST /api/user-create:** Registra um novo usuário. Controlador: `UserController@createUser`.
- **POST /api/user-login:** Faz a autenticação do usuário. Controlador: `AuthController@login`.

Certifique-se de ajustar as rotas e os controladores conforme a estrutura real do seu projeto.

## Execução

Para rodar a aplicação, siga os passos abaixo:

1. **Clone este repositório:**
git clone https://github.com/FilipeNSV/controle-estoque-mercado.git

2. **Navegue até o diretório do projeto:**
cd seu-repositorio

3. **Execute o servidor PHP local na porta desejada (especifique uma porta que não entre em conflito com o front-end):**
php -S localhost:8080 -t public

4. **Configure o arquivo .env com as informações do banco de dados e faça uma JWT_KEY:**
Exemplo:
DB_CONNECTION='pgsql'
DB_HOST='localhost'
DB_PORT='5432'
DB_DATABASE='db_market'
DB_USERNAME='postgres'
DB_PASSWORD='123'

JWT_KEY=FlhTMdzn7V8KxvFUdsdass61Vyj8To55AXpDE1yDjpsPIpJcjcdsadas3h0skxARpzq