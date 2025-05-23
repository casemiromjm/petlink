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
                        <span><?= htmlspecialchars($user['username']) ?> (ID: <?= htmlspecialchars((string)$user['rowid']) ?>)</span>
                        <form action="../actions/action_elevateUser.php" method="POST" class="admin-action-form">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars((string)$user['rowid']) ?>">
                            <?php if ((int)$user['isAdmin'] === 1): ?>
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

    <style>
        .admin-panel {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .admin-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }

        .admin-section h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .user-list, .category-list {
            list-style: none;
            padding: 0;
        }

        .user-list li, .category-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
        }

        .user-list li:last-child, .category-list li:last-child {
            border-bottom: none;
        }

        .admin-action-form {
            display: inline-block;
            margin-left: 15px;
        }

        .elevate-button {
            background-color: #28a745; /* Verde */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .elevate-button:hover {
            background-color: #218838;
        }

        .demote-button {
            background-color: #ffc107; /* Amarelo/Laranja */
            color: #333;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .demote-button:hover {
            background-color: #e0a800;
        }

        .add-category-form {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .add-category-form input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-category-form button {
            background-color: #007bff; /* Azul */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-category-form button:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
<?php } ?>
