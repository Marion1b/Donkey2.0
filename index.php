<?php


// charge l'autoload de composer
require "vendor/autoload.php";

session_start();

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->handleRequest($_GET);

$csrf = new CSRFTokenManager();

if(empty($_SESSION["csrf_token"])){
    $_SESSION["csrf_token"] = $csrf->generateCSRFToken();
}

$randomId = rand(10000, 99999);

if(empty ($_SESSION["id"])){
    $_SESSION["id"]=$randomId;
}