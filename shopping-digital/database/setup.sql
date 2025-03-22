CREATE DATABASE shopping_digital;
USE shopping_digital;

-- Tabela de Usuários (Clientes, Lojistas, Entregadores, Admins)
    CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'lojista', 'entregador') NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(15),
    endereco VARCHAR(255),  -- Adicionada a coluna que estava faltando
    veiculo VARCHAR(50) -- Para entregadores
);


-- Tabela de Lojas (cada lojista pode ter sua loja)
CREATE TABLE loja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    endereco VARCHAR(255),
    telefone VARCHAR(20),
    horario_funcionamento VARCHAR(100),
    categoria VARCHAR(100),
    patrocinado TINYINT(1) DEFAULT 0,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

-- Tabela de Categorias de Produtos (cada loja pode ter categorias próprias)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loja_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    FOREIGN KEY (loja_id) REFERENCES lojas(id) ON DELETE CASCADE
);

-- Tabela de Produtos (ligados a uma loja e uma categoria específica)
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loja_id INT NOT NULL,
    categoria_id INT NULL,  -- Permite que a categoria seja opcional
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    imagem VARCHAR(255) NULL,
    FOREIGN KEY (loja_id) REFERENCES lojas(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);


-- Tabela de Pedidos (cada pedido é vinculado a uma loja específica)
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    loja_id INT NOT NULL,
    endereco_entrega VARCHAR(255) NOT NULL, -- Cliente pode definir um novo endereço
    status ENUM('pendente', 'preparando', 'saiu_para_entrega', 'entregue', 'cancelado') DEFAULT 'pendente',
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (loja_id) REFERENCES lojas(id) ON DELETE CASCADE
);

-- Tabela de Itens do Pedido (para cada produto dentro de um pedido)
CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Tabela de Entregas (ligando pedidos a entregadores)
CREATE TABLE entregas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    entregador_id INT NOT NULL,
    status ENUM('pendente', 'a_caminho', 'entregue', 'cancelado') DEFAULT 'pendente',
    data_entrega TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (entregador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
