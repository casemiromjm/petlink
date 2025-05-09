<?php
declare(strict_types = 1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once('../database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        // Validate and sanitize input
        $title = htmlspecialchars(trim($_POST['titulo']));
        $description = htmlspecialchars(trim($_POST['descricao']));
        $serviceType = htmlspecialchars(trim($_POST['tipo']));
        $price = floatval($_POST['preco']);
        $pricePeriod = htmlspecialchars(trim($_POST['preco-por']));
        $username = 'maria123'; // Replace with the logged-in user's username
        $animals = $_POST['animais'] ?? [];

        // Handle image upload
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

        // Insert ad into the database
        $stmt = $db->prepare('
            INSERT INTO ads (title, username, description, service_type, price, price_period, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$title, $username, $description, $serviceType, $price, $pricePeriod, $imagePath]);

        // Get the last inserted ad ID
        $adId = $db->lastInsertId();

        // Insert associated animals into the ad_animals table
        $stmtAnimal = $db->prepare('INSERT INTO ad_animals (ad_id, animal_type) VALUES (?, ?)');
        foreach ($animals as $animal) {
            $stmtAnimal->execute([$adId, htmlspecialchars($animal)]);
        }

        // Redirect to a success page or back to the ad creation page
        header('Location: ../pages/adCreate.php?success=1');
        exit;
    } catch (Exception $e) {
        // Handle errors (e.g., log them and redirect to an error page)
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>