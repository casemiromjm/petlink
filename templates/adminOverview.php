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
