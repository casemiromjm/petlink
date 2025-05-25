<?php
    declare(strict_types = 1);
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/sidebar.php')
?>

<?php function drawEditAd(array $ad, array $associated_animals, int $success, string $csrf_token): void { ?>
<main class="ads-layout">
    <?php if ($success === 1): ?>
    <div id="success-message" class="success-message">Anúncio editado com sucesso</div>
    <?php endif; ?>

    <section class="ad-content">
        <div class="form-container">
            <h2>Editar Anúncio</h2>
            <form action="../actions/action_editAd.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">

                <input type="hidden" name="ad_id" value="<?= htmlspecialchars((string)$ad['ad_id']) ?>">

                <label for="animal-picture">Fotografia</label>
                    <div class="upload-box">
                        <input type="file" id="ad-picture" name="ad-picture" accept="image/*">
                        <?php if (!empty($ad['ad_picture'])): ?>
                            <img src="<?= htmlspecialchars(str_replace('./', '../', $ad['ad_picture'])) ?>" alt="Ad image" style="max-width:100px;">
                        <?php endif; ?>
                    </div>

                <label for="ad-title">Título</label>
                <input type="text" id="ad-title" name="title" value="<?= htmlspecialchars($ad['title']) ?>" required>

                <label for="ad-description">Descrição</label>
                <textarea id="ad-description" name="description" required><?= htmlspecialchars($ad['description']) ?></textarea>

                <label for="ad-price">Preço</label>
                <input type="number" id="ad-price" name="price" step="0.01" value="<?= htmlspecialchars((string)$ad['price']) ?>" required>

                <div class="animal-checkboxes">
                    <?php
                    $animalOptions = [
                        1 => 'Cães',
                        2 => 'Gatos',
                        3 => 'Pássaros',
                        4 => 'Roedores',
                        5 => 'Répteis',
                        6 => 'Peixes',
                        7 => 'Furões',
                        8 => 'Coelhos'
                    ];

                    foreach ($animalOptions as $value => $label): ?>
                        <label>
                            <input type="checkbox"
                                name="animais[]"
                                value="<?= $value ?>"
                                <?= in_array($value, $associated_animals) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <button type="submit">Salvar Alterações</button>
            </form>

            <form action="../actions/action_deleteAd.php" method="post" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">

                <input type="hidden" name="ad_id" value="<?= htmlspecialchars((string)$ad['ad_id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <button type="submit" class="delete-button" onclick="return confirm('Tens certeza que queres deletar este anúncio PERMANENTEMENTE?')">Eliminar Anúncio</button>
            </form>
        </div>
    </section>
</main>
<?php } ?>
