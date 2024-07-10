<?php

// on initialize la connexion à la DB avec les valeurs de DNS user et password
$dns = 'mysql:host=localhost;dbname=blog-dyma';
$user = 'root';
$password = '';

// on gere les exceptions par try catch()
try {
    $pdo = new PDO ($dns, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $exception) {
    echo "ERROR:" . $exception->getMessage();
    return false;
}
return $pdo;