<?php
namespace Api\Models;

use Api\Core\Database;
use PDO;

class Store {
    public function create(array $data): bool {
        $pdo = Database::getInstance();
        $sql = "INSERT INTO stores (nome, tipo, idProprietario) VALUES (:nome, :tipo, :idProprietario)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nome'           => $data['nome'],
            ':tipo'           => $data['tipo'],
            ':idProprietario' => $data['idProprietario']
        ]);
    }

    public function existeLoja(string $nome, string $tipo, int $idUsuarioLogado): bool {
        $pdo = Database::getInstance();
        $sql = "SELECT COUNT(*) FROM stores WHERE nome = :nome AND tipo = :tipo AND idProprietario = :idProprietario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':tipo' => $tipo,
            ':idProprietario' => $idUsuarioLogado
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function obterComRestricoes($restricoes = array()): array {
        $pdo = Database::getInstance();
        $sql = "SELECT * FROM stores WHERE 1";
        $parametros = array();

        if (is_numeric($restricoes['idProprietario'])) {
            $sql .= " AND idProprietario = :idProprietario ";
            $parametros["idProprietario"] = $restricoes["idProprietario"];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
