<?php
namespace App\Models;

use App\Core\Database;

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

}
