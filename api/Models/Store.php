<?php
namespace Api\Models;

use Api\Core\Database;
use PDO;

class Store {
    public function create(array $data): bool {
        $pdo = Database::getInstance();
        $sql = "INSERT INTO stores (nome, tipo) VALUES (:nome, :tipo)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nome'    => $data['nome'],
            ':tipo'    => $data['tipo']
        ]);
    }

    public function existeLoja(string $nome, string $tipo): bool {
        $pdo = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM stores WHERE nome = :nome AND tipo = :tipo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':tipo' => $tipo
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getAllStores(): array {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM stores ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
