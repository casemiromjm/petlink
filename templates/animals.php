<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<?php require_once(__DIR__ .'/../templates/sidebar.php'); ?>

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
                SELECT
                    ua.rowid AS animal_id_from_db, -- <--- MUDANÇA AQUI: Dar um alias explícito ao rowid
                    ua.name,
                    ua.age,
                    at.animal_name,
                    ua.animal_picture
                FROM user_animals ua
                JOIN Animal_types at ON ua.species = at.animal_id
                WHERE ua.user_id = ?
            ');
            $stmt->execute([$userId]);
            $animals = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($animals as $animal):
                $animalRowId = isset($animal['animal_id_from_db']) && is_numeric($animal['animal_id_from_db']) && (int)$animal['animal_id_from_db'] > 0 ? (int)$animal['animal_id_from_db'] : null;

                if ($animalRowId !== null):
            ?>
                <a class="animal-card-link" href="../pages/editAnimal.php?id=<?= htmlspecialchars((string)$animalRowId) ?>">
                    <div class="animal-card">
                        <div class="animal-image-container">
                            <img src="<?= htmlspecialchars(str_replace('./', '../', $animal['animal_picture'] ?? '../resources/default_animal.png')) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                        </div>
                        <div class="animal-info">
                            <h3><?= htmlspecialchars($animal['name']) ?></h3>
                            <p class="animal-type"><?= htmlspecialchars(translateAnimalType($animal['animal_name'])) ?></p>
                            <p class="animal-age"><?= htmlspecialchars((string)$animal['age']) ?> anos</p>
                        </div>
                    </div>
                </a>
            <?php
                endif;
            endforeach; ?>
            <a class="animal-card-link" href="../pages/addAnimal.php">
                <div class="animal-card add-animal">
                    <div class="add-icon">+</div>
                </div>
                <p>Adicionar animal</p>
            </a>
        </div>
    </section>
</body>
<?php } ?>
