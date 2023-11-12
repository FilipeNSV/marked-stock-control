-- Criação da tabela 'users'
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);

-- Criação da tabela 'product_type'
CREATE TABLE product_type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    tax DECIMAL(8, 3) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);

-- Criação da tabela 'product'
CREATE TABLE product (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    product_type_id INTEGER REFERENCES product_type(id),
    value DECIMAL(8, 3) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);

-- Criação da tabela 'transactions'
CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    transaction_type VARCHAR(255) NOT NULL,
    supplier_name VARCHAR(255),
    customer_name VARCHAR(255),
    product_id INTEGER REFERENCES product(id),
    amount INTEGER NOT NULL,
    value_without_tax DECIMAL(8, 3),
    total_tax DECIMAL(8, 3),
    total_value DECIMAL(8, 3) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);

-- Criação da tabela 'stock'
CREATE TABLE stock (
    id SERIAL PRIMARY KEY,
    transaction_id INTEGER REFERENCES transactions(id),
    product_id INTEGER REFERENCES product(id),
    value_without_tax DECIMAL(8, 3),
    total_tax DECIMAL(8, 3),
    total_value DECIMAL(8, 3),
    status VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);
