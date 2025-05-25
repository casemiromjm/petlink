<?php
declare(strict_types = 1);

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/users.class.php');
require_once(__DIR__.'/../database/animal.class.php');
require_once(__DIR__.'/../database/service.class.php');
require_once(__DIR__.'/../templates/admin.php');
require_once(__DIR__.'/../templates/layout.php');
$db = getDatabaseConnection();

$users = User::getAllUsers($db);
$animalTypes = Animal_type::getAnimalSpecies($db);
$services = Service::getAllServices($db);

?>
<section class="admin-section">
    <h3>Gerir Categorias & Serviços</h3>
        <div class="toggle-section">
            <h3 class="toggle-header" onclick="toggleVisibility('animal-species-content')">Gerir Espécies de Animais <span class="toggle-icon">+</span></h3>
                <div id="animal-species-content" class="toggle-content" style="display: none;"> <?php if (empty($animalTypes)): ?>
                        <p>Nenhuma espécie de animal encontrada.</p>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome da Espécie</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($animalTypes as $type):?>
                                        <tr>
                                            <td><?= htmlspecialchars((string)$type['animal_id']) ?></td>
                                            <td><?= htmlspecialchars($type['animal_name']) ?></td>
                                            <td>
                                                <form action="/actions/action_deleteAnimalType.php" method="post" class="admin-action-form" onsubmit="return confirm('Tem a certeza que quer eliminar esta espécie?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                                                    <input type="hidden" name="type_id" value="<?= htmlspecialchars((string)$type['animal_id']) ?>">
                                                    <button type="submit" style="background-color: #dc3545; color: white;">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                            <h4>Adicionar Nova Espécie</h4>
                                <form action="/actions/action_addAnimalType.php" method="post" class="add-category-form">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                                    <div class="form-group">
                                        <label for="new_category_name">Nome da Espécie:</label>
                                        <input type="text" id="new_category_name" name="name" required placeholder="Ex: Cão, Gato, Pássaro">
                                    </div>
                                    <button type="submit" class="button-primary">Adicionar Espécie</button>
                                </form>
                </div>
        </div>
        <div class="toggle-section">
            <h3 class="toggle-header" onclick="toggleVisibility('services-content')">Gerir Serviços <span class="toggle-icon">+</span></h3>
                <div id="services-content" class="toggle-content" style="display: none;">
                    <?php if (empty($services)): ?>
                        <p>Nenhum serviço encontrado.</p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Serviço</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td><?= htmlspecialchars((string)$service->service_id) ?></td>
                                        <td><?= htmlspecialchars($service->service_name) ?></td>
                                        <td>
                                            <form action="/actions/action_deleteService.php" method="post" class="admin-action-form" onsubmit="return confirm('Tem a certeza que quer eliminar este serviço?');">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                                                <input type="hidden" name="service_id" value="<?= htmlspecialchars((string)$service->service_id) ?>">
                                            <button type="submit" style="background-color: #dc3545; color: white;">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <h4>Adicionar Novo Serviço</h4>
                        <form action="/actions/action_addService.php" method="post" class="add-category-form">
                            <div class="form-group">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8'); ?>">
                                <label for="new_service_name">Nome do Serviço:</label>
                                <input type="text" id="new_service_name" name="name" required placeholder="Ex: Passeio, Petsitting, Veterinário">
                            </div>
                            <button type="submit" class="button-primary">Adicionar Serviço</button>
                        </form>
                </div>
        </div>
</section>
