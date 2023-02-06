<?php
require_once '../vendor/autoload.php';

use Backend\database\Connection;
use Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

$hash = password_hash($password, PASSWORD_DEFAULT);

$pdo = Connection::connect();

$prepare = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$prepare->execute(['email' => $email]);

$userFound = $prepare->fetch();

// echo json_encode($userFound);


if(!$userFound) {
  http_response_code(401);
}

if(!password_verify($userFound->password, $hash)) {
  http_response_code(401);
}

$payload = [
  "exp" => time() + 10,
  "iat" => time(),
  "email" => $email
];

$encode = JWT::encode($payload, $_ENV['SECRET_KEY'], 'HS256');

echo json_encode($encode);