<?php
    declare(strict_types = 1);
    require_once(__DIR__ . '/../templates/sidebar.php');
    require_once(__DIR__ . '/../init.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    function drawEditAd($csrf_token): void {

        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $db = getDatabaseConnection();
        $userId = $_SESSION['user_id'];
        $adId = isset($_GET['id']) ? intval($_GET['id']) : null;

        if (!$adId) {
            die('Anúncio não especificado.');
        }

        $stmt = $db->prepare('SELECT is_admin FROM users WHERE user_id = ?');
        $stmt->execute([$userId]);
        $isAdmin = (bool)$stmt->fetchColumn();

        if ($isAdmin) {
            $stmt = $db->prepare('SELECT * FROM Ads WHERE ad_id = ?');
            $stmt->execute([$adId]);
        } else {
            $stmt = $db->prepare('SELECT * FROM Ads WHERE ad_id = ? AND freelancer_id = ?');
            $stmt->execute([$adId, $userId]);
        }

        $ad = $stmt->fetch();

        if (!$ad) {
            die('Anúncio não encontrado.');
        }

        $animalStmt = $db->prepare('SELECT animal_id FROM Ad_animals WHERE ad_id = ?');
        $animalStmt->execute([$adId]);
        $associatedAnimals = $animalStmt->fetchAll(PDO::FETCH_COLUMN);

        $success = isset($_GET['success']) ? intval($_GET['success']) : 0;
?>

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
                                <?= in_array($value, $associatedAnimals) ? 'checked' : '' ?>>
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
