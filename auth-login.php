<?php
require_once './database/database.php';
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_PASSWORD_TOO_SHORT = 'le mot doit etre composé de 6 character au moins';
const ERROR_INVALID_EMAIL = "l'email n'est pas valide";
const ERROR_UNKNOWN_EMAIL = "l'email n'est pas enregistré";
const ERROR_PASSWORD_MISMATCH = 'les mots de passe ne matchent pas';

$errors = [
  'email' => '',
  'password' => '',
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
    ]);
    
    $email = $input['email'] ?? '';
    $password = $_POST['password'] ?? '';
    

   
    if(!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_INVALID_EMAIL;
    }
    if(!$password) {
        $errors['password'] = ERROR_REQUIRED;
    }
   

  if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
    $statementUser = $pdo->prepare('SELECT * FROM user WHERE email=:email');
    $statementUser->bindValue(':email', $email);
    $statementUser->execute();
    $user = $statementUser->fetch();

    if(!$user) {
      $errors['email'] = ERROR_UNKNOWN_EMAIL;
    } else {
      if(!password_verify($password, $user['password'])) {
        $errors['password'] = ERROR_PASSWORD_MISMATCH;
    } else {
      $statementSession = $pdo->prepare('INSERT INTO session VALUES (
        DEFAULT,
        :userid)');
        $statementSession->bindVAlue(':userid', $user['id']);
        $statementSession->execute();
        $sessionId = $pdo->lastInsertId();
        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
      header('Location: /');
    }
  }

}
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <?php require_once 'includes/head.php' ?>
  <link rel="stylesheet" href="/public/css/auth-login.css">
  <title>Connexion</title>
</head>

<body>
  
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
    <div class="block p-20 form-container">
        <h1>Connexion</h1>
        <form action="/auth-login.php" , method="POST">
          <div class="form-control">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?= $email ?? '' ?>">
            <?php if ($errors['email']) : ?>
              <p class="text-danger"><?= $errors['email'] ?></p>
            <?php endif; ?>
          </div>
        
          <div class="form-control">
            <label for="password">Mot de passe</label>
            <input type="text" name="password" id="password">
            <?php if ($errors['password']) : ?>
              <p class="text-danger"><?= $errors['password'] ?></p>
            <?php endif; ?>
          </div>


          <div class="form-actions">
            <a href="/" class="btn btn-secondary" type="button">Annuler</a>
            <button class="btn btn-primary" type="submit">Connexion</button>
          </div>
        </form>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>

</body>

</html>
