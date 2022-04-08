<?php

namespace root\Organization;

use PDO;
use root\Base\Database;

class OrganizationGateway
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::getInstance()->getConnection();
    }

    public function findByName(string $name): array
    {
        $stmt = $this->database->prepare('SELECT * FROM organization WHERE name = :name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByLikeName(string $name): array
    {
        $stmt = $this->database->prepare('SELECT * FROM organization WHERE name LIKE ?');
        $stmt->bindValue(1, "%$name%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDaughters(int $parentId): array
    {
        $stmt = $this->database->prepare('SELECT * FROM organization WHERE parent = :parent');
        $stmt->bindParam(':parent', $parentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParent(int $id): array
    {
        $stmt = $this->database->prepare('SELECT * FROM organization WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}