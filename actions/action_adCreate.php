<?php
declare(strict_types = 1);
session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php'); // Use absolute path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        $title = htmlspecialchars(trim($_POST['titulo']));
        $description = htmlspecialchars(trim($_POST['descricao']));
        $serviceTypeId = intval($_POST['tipo']); 
        $price = floatval($_POST['preco']);
        $pricePeriod = htmlspecialchars(trim($_POST['preco-por']));
        $username = $_SESSION['username'];
        $animals = $_POST['animais'] ?? [];

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../resources/'; 
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = './resources/' . $fileName; 
            }
        }

        $stmt = $db->prepare('
            INSERT INTO ads (title, username, description, price, price_period, image_path)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$title, $username, $description, $price, $pricePeriod, $imagePath]);

        $adId = $db->lastInsertId();

        $stmtService = $db->prepare('INSERT INTO ad_services (ad_id, service_id) VALUES (?, ?)');
        $stmtService->execute([$adId, $serviceTypeId]);

        $stmtAnimal = $db->prepare('INSERT INTO ad_animals (ad_id, animal_id) VALUES (?, ?)');
        foreach ($animals as $animal) {
            $stmtAnimal->execute([$adId, intval($animal)]);
        }

        header("Location: ../pages/adDetails.php?id=$adId&success=1");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>