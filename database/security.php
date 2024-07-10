<?php

function isLoggedIn() {
    global $pdo;
    $sessionId = $_COOKIE['session'] ?? '';
    if($sessionId) {
        $statementSession = $pdo->prepare('SELECT * FROM `session` WHERE id=:id');
        $statementSession->bindValue(':id', $sessionId);
        $statementSession->execute();
        $session = $statementSession->fetch();
        if($session) {
            $statmentUser = $pdo->prepare('SELECT * FROM user WHERE id=:id');
            $statmentUser->bindValue(':id', $session['userid']);
            $statmentUser->execute();
            $user = $statmentUser->fetch();
        }
    }
    return $user ?? false;
}
