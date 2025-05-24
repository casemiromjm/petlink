<?php
declare(strict_types = 1);


function drawAdminPanel(string $currentTab, array $users, array $animalTypes, array $overview): void { ?>
    <section class="admin-panel">
        <h2>Painel de Administração</h2>

        <?php switch ($currentTab):
            case 'users': ?>
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
                                        <td><?= htmlspecialchars((string)$user['user_id']) ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= ((int)$user['is_admin'] === 1) ? 'Sim' : 'Não' ?></td>
                                        <td>
                                            <?php if ((int)$user['user_id'] !== (int)$_SESSION['user_id']): ?>
                                                <form action="/actions/action_elevateUser.php" method="post" class="admin-action-form">
                                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars((string)$user['user_id']) ?>">
                                                    <input type="hidden" name="current_status" value="<?= htmlspecialchars((string)$user['is_admin']) ?>">
                                                    <button type="submit" name="action" value="toggle_admin">
                                                        <?= ((int)$user['is_admin'] === 1) ? 'Remover Admin' : 'Tornar Admin' ?>
                                                    </button>
                                                </form>
                                                <form action= "/actions/action_deleteUser.php" method="post" class="admin-action-form" onsubmit="return confirm('Tem a certeza que quer eliminar este utilizador?');">
                                                    <input type="hidden" name="user_id" value="<?= htmlspecialchars((string)$user['user_id']) ?>">
                                                    <button type="submit" name="action" value="delete_user" style="background-color: #dc3545; color: white;">Eliminar</button>
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
            <?php break; ?>

            <?php case 'categories': ?>
                <section class="admin-section">
                    <h3>Gerir Categorias de Animais</h3>
                    <?php if (empty($animalTypes)): ?>
                        <p>Nenhuma categoria de animal encontrada.</p>
                    <?php else: ?>
                        <ul class="category-list">
                            <?php foreach ($animalTypes as $type): ?>
                                <li>
                                    <span><?= htmlspecialchars($type['name']) ?></span>
                                    <form action="../actions/action_delete_animal_type.php" method="post" class="admin-action-form" onsubmit="return confirm('Tem a certeza que quer eliminar esta categoria?');">
                                        <input type="hidden" name="type_id" value="<?= htmlspecialchars((string)$type['id']) ?>">
                                        <button type="submit" style="background-color: #dc3545; color: white;">Eliminar</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <h4>Adicionar Nova Categoria</h4>
                    <form action="../actions/action_add_animal_type.php" method="post">
                        <label for="new_category_name">Nome da Categoria:</label>
                        <input type="text" id="new_category_name" name="name" required>
                        <button type="submit">Adicionar Categoria</button>
                    </form>
                </section>
            <?php break; ?>

            <?php case 'overview': ?>
                <section class="admin-section">
                    <h3>Visão Geral do Sistema</h3>
                    <p>Bem-vindo ao painel de administração! Aqui pode supervisionar e garantir o bom funcionamento de todo o sistema.</p>
                    <ul>
                        <li>Total de Utilizadores Registados: **<?= htmlspecialchars((string)$systemOverviewData['total_users']) ?>**</li>
                        <li>Total de Animais Registados: **<?= htmlspecialchars((string)$systemOverviewData['total_animals']) ?>**</li>
                        </ul>
                    <h4>Ações do Sistema:</h4>
                    <p>Aqui poderias ter botões para tarefas como:</p>
                    <ul>
                        <li><button disabled>Gerar Relatório de Atividade</button></li>
                        <li><button disabled>Limpar Cache do Sistema</button></li>
                        <li><button disabled>Ver Logs de Erro</button></li>
                    </ul>
                </section>
            <?php break; ?>

            <?php default: ?>
                <section class="admin-section">
                    <h3>Bem-vindo ao Painel de Administração</h3>
                    <p>Selecione uma opção na barra lateral para gerir o sistema.</p>
                </section>
            <?php break; ?>
        <?php endswitch; ?>
        </section>
<?php } ?>
