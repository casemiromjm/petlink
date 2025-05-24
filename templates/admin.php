<?php
declare(strict_types = 1);

function drawAdminPanel(string $currentTab, array $users, array $animalTypes, array $services ,array $overview): void { ?>
    <section class="admin-panel">
        <h2>Painel de Administração</h2>
        <?php switch ($currentTab):
            case 'users':
                include __DIR__ . '/adminUserTab.php';
                break; ?>
            <?php break; ?>

            <?php case 'categories':
                include __DIR__ . '/adminCategoriesTab.php';
                break; ?>
            <?php break; ?>

            <?php case 'overview':
                include __DIR__ . '/adminOverview.php';
                break; ?>
            <?php default: ?>
                <section class="admin-section">
                    <h3>Bem-vindo ao Painel de Administração</h3>
                    <p>Selecione uma opção na barra lateral para gerir o sistema.</p>
                </section>
            <?php break; ?>
        <?php endswitch; ?>
        </section>
<?php } ?>
