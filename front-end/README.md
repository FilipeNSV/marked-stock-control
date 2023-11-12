# Front-end da Aplicação de Controle de Estoque - Mercado

Este é o repositório do front-end da aplicação de Controle de Estoque, desenvolvido usando Vue.js 3. Esta aplicação possui diversas funcionalidades essenciais para gerenciar o estoque de produtos, incluindo Controle de Estoque, CRUD de Produtos, CRUD de Tipos de Produto e Registro de Transações de Compra, Venda e Autenticação para as rotas. As funcionalidades são interligadas de maneira que os produtos estão vinculados aos tipos de produtos, e as transações são registradas com base nesses produtos. O controle de estoque fornece um resumo claro do que foi comprado e vendido.

## Tecnologias Utilizadas

Aplicação de Controle de Estoque para o mercado foi desenvolvida utilizando as seguintes tecnologias, frameworks e bibliotecas:

- **Vue.js 3:**
- **Bootstrap 5:**
- **Axios:**

## Requisitos de Configuração

Antes de executar este projeto, certifique-se de ter instalado o Node.js em sua máquina. Você pode baixá-lo em [nodejs.org](https://nodejs.org/).

## Instalação

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local:

1. **Clone este repositório:**
git clone https://github.com/FilipeNSV/controle-estoque-mercado.git

2. **Navegue até o diretório do projeto:**
cd seu-repositorio

3. **Instale as dependências do projeto com npm:**
npm install

## Configuração da API Back-End
Antes de iniciar a aplicação, é necessário configurar a URL base da API back-end. Para fazer isso, siga os passos abaixo:

1. **Crie um arquivo chamado .env.local na raiz do diretório do projeto.**

2. **Insira a URL base da API back-end no arquivo .env.local.**
Por exemplo: VUE_APP_API_BASE_URL=http://localhost:8090/api/

## Execução
Após a instalação e configuração, você pode executar a aplicação em modo de desenvolvimento usando o seguinte comando: 
npm run serve ou npm run serve -- --port 3000 (para especificar a porta e não ficar igual ao back-end)

## Funcionalidades Principais

### Controle de Estoque

O Controle de Estoque fornece um resumo das quantidades de produtos disponíveis com base nas transações de compra e venda registradas.

### CRUD de Produtos

- **Listagem de Produtos:** Visualize todos os produtos cadastrados, incluindo detalhes como nome, tipo e quantidade em estoque.
- **Cadastro de Produto:** Adicione novos produtos com informações como nome, descrição, preço e tipo.
- **Edição de Produto:** Atualize as informações de um produto existente.
- **Exclusão de Produto:** Faz um softDelete(apenas marca a coluna deleted_at).

### Tipos de Produto

- **Listagem de Tipos de Produto:** Veja uma lista de todos os tipos de produtos cadastrados.
- **Cadastro de Tipo de Produto:** Crie novos tipos de produtos para categorizar seus produtos.
- **Exclusão de Tipo de Produto:** Faz um softDelete(apenas marca a coluna deleted_at).

### Transações de Compra e Venda

- **Registro de Compra:** Registre a compra de produtos, especificando a quantidade, data e fornecedor.
- **Registro de Venda:** Registre a venda de produtos, especificando a quantidade, data e cliente.
- **Histórico de Transações:** Veja um histórico completo de todas as transações de compra e venda realizadas.
