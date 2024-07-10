<?php
$pdo = require_once __DIR__ .'/database/database.php';
$sessionId = $_COOKIE['session'];
if($sessionId) {
    $statementDelete = $pdo->prepare('DELETE FROM session WHERE id=:id');
    $statementDelete->bindValue(':id', $sessionId);
    $statementDelete->execute();
    setcookie('session', '', time() -1 );
    header('Location: /auth-login.php');
}
