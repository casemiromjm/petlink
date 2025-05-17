<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<?php require_once('../templates/sidebar.php'); ?>

<?php
function translateAnimalType(string $animalType): string {
    $translations = [
        'Cães' => 'Cão',
        'Gatos' => 'Gato',
        'Roedores' => 'Roedor',
        'Pássaros' => 'Pássaro',
        'Répteis' => 'Réptil',
        'Peixes' => 'Peixe',
        'Furões' => 'Furão',
        'Coelhos' => 'Coelho'
    ];

    return $translations[$animalType] ?? $animalType;
}
?>

<?php function drawAnimals(PDO $db, int $userId): void { ?>
<body class="animals">
    <section class="animal-list">
        <h2>Os meus animais</h2>
        <div class="animal-cards">
            <?php
            $stmt = $db->prepare('
                SELECT ua.rowid, ua.name, ua.age, at.animal_name, ua.animal_picture
                FROM user_animals ua
                JOIN Animal_types at ON ua.species = at.animal_id
                WHERE ua.user_id = ?
            ');
            $stmt->execute([$userId]);
            $animals = $stmt->fetchAll();

            foreach ($animals as $animal): ?>
                <a class="animal-card-link" href="../pages/editAnimal.php?id=<?= htmlspecialchars((string)$animal['rowid']) ?>">
                    <div class="animal-card">
                        <img src="<?= htmlspecialchars(str_replace('./', '../', $animal['animal_picture'] ?? '../resources/default_animal.png')) ?>" alt="Animal">
                        <h3><?= htmlspecialchars($animal['name']) ?></h3>
                        <p><?= htmlspecialchars(translateAnimalType($animal['animal_name'])) ?> • <?= htmlspecialchars((string)$animal['age']) ?> anos</p>
                    </div>
                </a>
            <?php endforeach; ?>
            <a class="animal-card-link" href="../pages/addAnimal.php">
                <div class="animal-card add-animal">
                    <div class="add-icon">+</div>
                    <p>Adicionar animal</p>
                </div>
            </a>
        </div>
    </section>
</body>
<?php } ?>