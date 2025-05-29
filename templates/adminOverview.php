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

$overview = [
    'total_users' => count($users),
    'total_animals' => count($animalTypes),
    'total_services' => count($services),
    'recent_activity' => [],
];
?>
<section class="admin-section">
    <h3>Visão Geral do Sistema</h3>
    <p>Bem-vindo ao painel de administração! Aqui pode supervisionar e garantir o bom funcionamento de todo o sistema.</p>
    <ul class = "data">
        <li>Total de Utilizadores Registados: <?= htmlspecialchars((string)$overview['total_users']) ?></li>
        <li>Total de Espécies Disponíveis: <?= htmlspecialchars((string)$overview['total_animals']) ?></li>
        <li>Total de Serviços Disponíveis: <?= htmlspecialchars((string)$overview['total_services']) ?></li>
    </ul>
<br>
    <ul class="actions">
    <li><a href="/actions/action_userReport.php"><button>Gerar Relatório de Usuários</button></a></li>
    <li><a href="/actions/action_adReport.php"><button>Gerar Relatório de Anúncios</button></a></li>

    </ul>
</section>
