<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Slim\Factory\AppFactory;
use App\Controllers\StoreController;
use App\Controllers\AuthController;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Rotas
$app->get('/loja/nova', [StoreController::class, 'create']);
$app->post('/loja/salvar', [StoreController::class, 'store']);

$app->get('/', function ($request, $response, $args) {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/Views');
    $twig = new \Twig\Environment($loader);

    $html = $twig->render('home.twig');
    $response->getBody()->write($html);
    return $response;
});


$app->get('/login', [AuthController::class, 'showLogin']);
$app->post('/login', [AuthController::class, 'login']);

$app->get('/register', [AuthController::class, 'showRegister']);
$app->post('/register', [AuthController::class, 'register']);

$app->get('/logout', [AuthController::class, 'logout']);

$app->get('/dashboard', function ($request, $response) {
    if (empty($_SESSION['usuario'])) {
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/Views');
    $twig = new \Twig\Environment($loader);
    $html = $twig->render('auth/dashboard.twig');
    $response->getBody()->write($html);
    return $response;
});

$app->run();