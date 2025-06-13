CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL
);
 
CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria VARCHAR(100) NOT NULL
);

CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  categoria_id INT NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  disponibilidade ENUM('disponível', 'indisponível') NOT NULL,

  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
