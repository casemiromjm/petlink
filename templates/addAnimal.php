<?php
<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAddAnimal(): void { ?>
<body class="add-animal">
    <section class="add-animal-form">
        <h2>Adicionar Animal</h2>
        <form action="../actions/action_addAnimal.php" method="post">
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" required>

            <label for="age">Idade</label>
            <input type="number" id="age" name="age" required>

            <label for="species">Espécie</label>
            <select id="species" name="species" required>
                <option disabled selected>Selecionar</option>
                <?php
                require_once('../database/connection.db.php');
                $db = getDatabaseConnection();
                $stmt = $db->query('SELECT animal_id, animal_name FROM Animal_types');
                while ($row = $stmt->fetch()): ?>
                    <option value="<?= htmlspecialchars((string)$row['animal_id']) ?>"><?= htmlspecialchars($row['animal_name']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Adicionar</button>
        </form>
    </section>
</body>
<?php } ?>