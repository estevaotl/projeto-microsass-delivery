CREATE DATABASE nuvix;
USE nuvix;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    dataCriacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE `stores` (
    `id` int NOT NULL AUTO_INCREMENT,
    `nome` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    `tipo` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
    PRIMARY KEY (`id`),
    idProprietario INT NOT NULL,
    FOREIGN KEY (idProprietario) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;