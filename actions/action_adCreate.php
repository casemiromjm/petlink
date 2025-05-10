<?php
declare(strict_types = 1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once('../database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        $title = htmlspecialchars(trim($_POST['titulo']));
        $description = htmlspecialchars(trim($_POST['descricao']));
        $serviceType = htmlspecialchars(trim($_POST['tipo']));
        $price = floatval($_POST['preco']);
        $pricePeriod = htmlspecialchars(trim($_POST['preco-por']));
        $username = 'maria123';
        $animals = $_POST['animais'] ?? [];

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../images/';
            $fileName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . uniqid() . '_' . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            }
        }

        $stmt = $db->prepare('
            INSERT INTO ads (title, username, description, service_type, price, price_period, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$title, $username, $description, $serviceType, $price, $pricePeriod, $imagePath]);

        $adId = $db->lastInsertId();

        $stmtAnimal = $db->prepare('INSERT INTO ad_animals (ad_id, animal_type) VALUES (?, ?)');
        foreach ($animals as $animal) {
            $stmtAnimal->execute([$adId, htmlspecialchars($animal)]);
        }

        header("Location: ../pages/adDetails.php?id=$adId&success=1");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>