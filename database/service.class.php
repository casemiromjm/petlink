<?php
declare(strict_types=1);

class Service {
    public int $service_id;
    public string $service_name;

    public function __construct(int $service_id, string $service_name) {
        $this->service_id = $service_id;
        $this->service_name = $service_name;
    }

    public static function getAllServices(PDO $db): array {
        try {
            $stmt = $db->prepare(
                'SELECT service_id, service_name
                FROM Services
                ORDER BY service_id ASC');
            $stmt->execute();
            $services = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $services[] = new Service($row['service_id'], $row['service_name']);
            }
            return $services;
        } catch (PDOException $e) {
            error_log("Erro PDO em Service::getAllServices: " . $e->getMessage());
            return [];
        }
    }

    static function addService(PDO $db, string $serviceName): bool {
        $stmt = $db->prepare('SELECT COUNT(*) FROM Services WHERE service_name = ?');
        $stmt->execute([$serviceName]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $db->prepare('INSERT INTO Services (service_name) VALUES (?)');
        return $stmt->execute([$serviceName]);
    }

    public static function deleteService(PDO $db, int $serviceId): bool {
        try {
            $stmt = $db->prepare('DELETE FROM Services WHERE id = ?');
            $stmt->execute([$serviceId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro PDO em Service::deleteService: " . $e->getMessage());
            throw $e;
        }
    }
}
?>
