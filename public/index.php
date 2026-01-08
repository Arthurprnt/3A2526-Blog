<?php
// Fichier : /public/index.php

// 1. Définir les constantes globales
define('ROOT_PATH', dirname(__DIR__));
define('LOG_PATH', ROOT_PATH . '/logs');

// 2. Charger l'autoloader de Composer
require_once ROOT_PATH . '/vendor/autoload.php';

// 3. Importer les classes nécessaires
use App\Core\SessionManager;
use App\Controllers\HomeController;
use App\Controllers\PostController;

// 4. Démarrer la session (via le Singleton)
SessionManager::getInstance();

// 5. Récupérer l'URL "propre"
$url = $_GET['url'] ?? '/';
$url = rtrim($url, '/');
if ($url === '') $url = '/';

// 6. Le Routeur
switch (true) {
    // Route 1 : Page d'accueil
    case $url === '/':
        (new HomeController())->index();
        break;

    // Route 2 : Article unique (ex: /post/12)
    case preg_match('/^post\/(\d+)$/', $url, $matches):
        $postId = (int) $matches[1];
        (new PostController())->show($postId);
        break;

    // Route 3 : Page À Propos (NOUVEAU)
    case $url === 'a-propos':
        (new HomeController())->about();
        break;

    // Route 4 : Page de Contact (NOUVEAU)
    case $url === 'contact':
        (new HomeController())->contact();
        break;
    
    // Route 5 : Page de Connexion
    case $url === 'connexion':
        (new HomeController())->connexion();
        break;
    
    // Route 6 : Page de Déconnexion
    case $url === 'deconnexion':
        (new HomeController())->deconnexion();
        break;

    // Route 7 : Page de Signup
    case $url === 'signup':
        (new HomeController())->signup();
        break;
    
    // Route 8 : Page de Dashboard
    case $url === 'dashboard':
        (new HomeController())->dashboard();
        break;

    case $url === 'delete_account':
        (new HomeController())->delete_account();
        break;
    
    case $url === 'creer':
        (new HomeController())->creer();
        break;

    case $url === 'edit':
        (new HomeController())->edit();
        break;

    case $url === 'admin-dashboard':
        (new HomeController())->admin();
        break;

    // Route final : 404
    default:
        (new HomeController())->error404();
        break;
}
