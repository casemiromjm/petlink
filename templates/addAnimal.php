<?php declare(strict_types = 1); ?>

<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAddAnimal(string $csrf_token): void { ?>
<main class="animals-layout">
    <aside class="side-nav">
        <?php drawNavbar(); ?>
    </aside>
    <section class="animals-content">
        <div class="form-container">
            <h2>Adicionar Animal</h2>
            <form action="../actions/action_addAnimal.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">

                <label for="animal-picture">Carregar fotografia</label>
                <div class="upload-box">
                    <input type="file" id="animal-picture" name="animal-picture" accept="image/*">
                </div>

                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required>

                <label for="species">Espécie</label>
                <select id="species" name="species" required>
                    <option disabled selected>Selecionar</option>
                    <?php
                    require_once(__DIR__ .'/../database/connection.db.php');
                    $db = getDatabaseConnection();
                    $stmt = $db->query('SELECT animal_id, animal_name FROM Animal_types');
                    while ($row = $stmt->fetch()): ?>
                        <option value="<?= htmlspecialchars((string)$row['animal_id']) ?>"><?= htmlspecialchars($row['animal_name']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="age">Idade</label>
                <input type="number" id="age" name="age" required>

                <button type="submit">Salvar</button>
            </form>
        </div>
    </section>
</main>
<?php } ?>
