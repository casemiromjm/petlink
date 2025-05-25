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

?>
<section class="admin-section">
    <h3>Gerir Utilizadores</h3>
    <?php if (empty($users)): ?>
        <p>Nenhum utilizador encontrado.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Admin</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td style="text-align:center"><?= htmlspecialchars((string)$user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= ((int)$user['is_admin'] === 1) ? 'Sim' : 'Não' ?></td>
                        <td>
                            <?php if ((int)$user['user_id'] !== (int)$_SESSION['user_id']): ?>
                                <form action="/actions/action_elevateUser.php" method="post" class="admin-action-form">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars((string)$user['user_id']) ?>">
                                    <input type="hidden" name="current_status" value="<?= htmlspecialchars((string)$user['is_admin']) ?>">
                                    <button type="submit" name="action" value="toggle_admin"
                                        class="admin-btn <?= ((int)$user['is_admin'] === 1) ? 'demote' : 'elevate' ?>">
                                        <?= ((int)$user['is_admin'] === 1) ? 'Remover Admin' : 'Tornar Admin' ?>
                                    </button>
                                </form>
                                <form action="/actions/action_deleteUser.php" method="post" class="admin-action-form" onsubmit="return confirm('Tem a certeza que quer eliminar este utilizador?');">
                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars((string)$user['user_id']) ?>">
                                    <button type="submit" name="action" value="delete_user" class="admin-btn delete">Eliminar</button>
                                </form>
                            <?php else: ?>
                                (Eu)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
