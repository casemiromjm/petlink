<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">
<?php require_once('../templates/sidebar.php'); ?>

<?php function drawAnimals(PDO $db, int $userId): void { ?>
<body class="animals">
    <section class="animal-list">
        <h2>Os meus animais</h2>
        <div class="animal-cards">
            <?php
            $stmt = $db->prepare('
                SELECT ua.name, ua.age, at.animal_name
                FROM user_animals ua
                JOIN Animal_types at ON ua.species = at.animal_id
                WHERE ua.user_id = ?
            ');
            $stmt->execute([$userId]);
            $animals = $stmt->fetchAll();

            foreach ($animals as $animal): ?>
                <div class="animal-card">
                    <img src="../resources/default_animal.png" alt="Animal">
                    <h3><?= htmlspecialchars($animal['name']) ?></h3>
                    <p><?= htmlspecialchars($animal['animal_name']) ?> • <?= htmlspecialchars((string)$animal['age']) ?> anos</p>
                </div>
            <?php endforeach; ?>
            <div class="animal-card add-animal">
                <a href="../pages/addAnimal.php">
                    <div class="add-icon">+</div>
                    <p>Adicionar animal</p>
                </a>
            </div>
        </div>
    </section>
</body>
<?php } ?>