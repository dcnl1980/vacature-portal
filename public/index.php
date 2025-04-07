<?php
/**
 * Index.php - Hoofdbestand van de Vacatureportal
 * 
 * Dit bestand handelt alle requests af en laadt de juiste views
 * 
 * @author: Chris van Steenbergen
 */

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../app/Models/Vacature.php';

$vacatureModel = new Vacature();

$page = $_GET['page'] ?? 'overzicht';

switch ($page) {
    case 'detail':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            header('Location: /index.php');
            exit;
        }
        
        $vacature = $vacatureModel->getVacatureById($id);
        
        require_once __DIR__ . '/../app/Views/detail.php';
        break;
        
    case 'overzicht':
    default:
        $searchTerm = isset($_GET['query']) ? trim($_GET['query']) : null;
        $location = isset($_GET['location']) ? trim($_GET['location']) : null;
        
        $vacatures = $vacatureModel->getVacatures($searchTerm, $location);
        
        require_once __DIR__ . '/../app/Views/overzicht.php';
        break;
} 