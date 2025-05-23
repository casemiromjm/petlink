<?php
declare(strict_types = 1);


function drawAdminPanel(array $users, array $animalTypes): void { ?>
    <section class="admin-panel">
        <h2>Painel de Administração</h2>

        <div class="admin-section">
            <h3>Gerir Utilizadores</h3>
            <ul class="user-list">
                <?php foreach ($users as $user): ?>
                    <li>
                        <span><?= htmlspecialchars($user['username']) ?> (ID: <?= htmlspecialchars((string)$user['user_id']) ?>)</span>
                        <form action="/actions/action_elevateUser.php" method="POST" class="admin-action-form">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars((string)$user['user_id']) ?>">
                            <?php if ((int)$user['is_admin'] === 1): ?>
                                <input type="hidden" name="new_admin_status" value="0">
                                <button type="submit" class="demote-button" onclick="return confirm('Tem certeza que deseja remover o status de administrador de <?= htmlspecialchars($user['username']) ?>?');">Remover Admin</button>
                            <?php else: ?>
                                <input type="hidden" name="new_admin_status" value="1">
                                <button type="submit" class="elevate-button" onclick="return confirm('Tem certeza que deseja elevar <?= htmlspecialchars($user['username']) ?> a administrador?');">Elevar a Admin</button>
                            <?php endif; ?>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="admin-section">
            <h3>Gerir Tipos de Animais</h3>
            <ul class="category-list">
                <?php foreach ($animalTypes as $type): ?>
                    <li><?= htmlspecialchars($type['animal_name']) ?> (ID: <?= htmlspecialchars((string)$type['animal_id']) ?>)</li>
                <?php endforeach; ?>
            </ul>
            <form action="../actions/action_addAnimalType.php" method="POST" class="admin-action-form add-category-form">
                <input type="text" name="animal_type_name" placeholder="Nome do novo tipo de animal" required>
                <button type="submit">Adicionar Tipo</button>
            </form>
        </div>

        </section>
<?php } ?>
