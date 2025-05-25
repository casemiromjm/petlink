<?php declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/animal.class.php');
require_once(__DIR__ . '/../database/service.class.php');

$db = getDatabaseConnection();
$animalSpecies = Animal_type::getAnimalSpecies($db);
$services = Service::getAllServices($db);
?>
<link rel="stylesheet" href="../stylesheets/style.css">

<?php function drawAdCreate(string $csrf_token) { global $animalSpecies, $services ;?>
    <section class="form-container">
        <h2>Anunciar Serviço</h2>
        <form id="ad-form" action="../actions/action_adCreate.php" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
            <label for="upload-box">Carregar fotografias</label>
            <div class="upload-box">
                <input type="file" id="imageUpload" name="image" accept="image/*">
            </div>
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="tipo">Tipo de serviço</label>
            <select id="service_id" name="service_id" required> <option disabled selected value="">Selecionar</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= htmlspecialchars((string)$service->service_id) ?>">
                        <?= htmlspecialchars($service->service_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="preco">Preço</label>
            <div id="preco-container">
                <input type="number" id="preco" name="preco" required>
                <label for="preco-por">€ /</label>
                <select id="preco-por" name="preco-por" required>
                    <option disabled selected value="">Selecionar</option>
                    <option>hora</option>
                    <option>dia</option>
                    <option>semana</option>
                    <option>mês</option>
                </select>
            </div>
            <label for="animais">Animais</label>
            <div class="animal-checkboxes">
            <?php foreach ($animalSpecies as $species): ?>
                <label>
                <input type="checkbox"
                    name="animais[]"
                    value="<?= htmlspecialchars((string)$species['animal_id']) ?>">
                    <?= htmlspecialchars($species['animal_name']) ?> </label>
            <?php endforeach; ?>
            </div>
            <button type="submit">Criar Anúncio</button>
        </form>
    </section>
<?php } ?>
