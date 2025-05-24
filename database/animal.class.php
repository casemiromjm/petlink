<?php

declare(strict_types=1);

class Animal_type {
    private int $animalId;
    private string $animalName;

    private function __construct(int $animalId, string $animalName) {
        $this->animalId = $animalId;
        $this->animalName = $animalName;
    }

    public function getAnimalId(): int {
        return $this->animalId;
    }

    public function getAnimalName(): string {
        return $this->animalName;
    }

    public static function getById(PDO $db, int $id): ?Animal_type {
        $stmt = $db->prepare('SELECT animal_id, animal_name FROM Animal_types WHERE animal_id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return new Animal_type((int)$data['animal_id'], $data['animal_name']);
    }

    public static function getByName(PDO $db, string $name): ?Animal_type {
        $stmt = $db->prepare('SELECT animal_id, animal_name FROM Animal_types WHERE animal_name = ?');
        $stmt->execute([$name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return new Animal_type((int)$data['animal_id'], $data['animal_name']);
    }

    public static function getAll(PDO $db): array {
        $stmt = $db->prepare('SELECT animal_id, animal_name FROM Animal_types ORDER BY animal_name ASC');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $animals = [];
        foreach ($data as $row) {
            $animals[] = new Animal_type((int)$row['animal_id'], $row['animal_name']);
        }
        return $animals;
    }

    public static function create(PDO $db, string $name) {
        try {
            $stmt = $db->prepare('INSERT INTO Animal_types (animal_name) VALUES (?)');
            $stmt->execute([$name]);
            return (int)$db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar tipo de animal: " . $e->getMessage());
            return false;
        }
    }

    public static function update(PDO $db, int $id, string $newName): bool {
        try {
            $stmt = $db->prepare('UPDATE Animal_types SET animal_name = ? WHERE animal_id = ?');
            return $stmt->execute([$newName, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar tipo de animal: " . $e->getMessage());
            return false;
        }
    }

    public static function deleteAnimalType(PDO $db, int $id): bool {
        try {
            $stmt = $db->prepare('DELETE FROM Animal_types WHERE animal_id = ?');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar tipo de animal: " . $e->getMessage());
            return false;
        }
    }

    static function  addAnimalType(PDO $db, string $animalName): bool {
        $stmt = $db->prepare('SELECT COUNT(*) FROM Animal_types WHERE animal_name = ?');
        $stmt->execute([$animalName]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $db->prepare('INSERT INTO Animal_types (animal_name) VALUES (?)');
        return $stmt->execute([$animalName]);
    }

    public static function getAnimalSpecies(PDO $db): array {
        $stmt = $db->prepare('SELECT animal_id, animal_name FROM Animal_types');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
