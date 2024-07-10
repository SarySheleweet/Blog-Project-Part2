<?php
require_once './database/database.php';
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TOO_SHORT = 'le champ est tres court';
const ERROR_PASSWORD_TOO_SHORT = 'le mot doit etre composé de 6 character au moins';
const ERROR_PASSWORD_MISMATCH = 'les mots de passe ne matchent pas';
const ERROR_INVALID_EMAIL = "l'email n'est pas valide";

$errors = [
  'firstname' => '',
  'lastname' => '',
  'email' => '',
  'password' => '',
  'confirmpassword' => ''
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST, [
        'firstname' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'lastname' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'email' => FILTER_SANITIZE_EMAIL,
    ]);
    $firstname = $input['firstname'] ?? '';
    $lastname = $input['lastname'] ?? '';
    $email = $input['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmpassword = $_POST['confirmpassword'] ?? '';

    if(!$firstname) {
        $errors['firstname'] = ERROR_REQUIRED; 
    } elseif(mb_strlen($firstname) < 3 ){
        $errors['firstname'] = ERROR_TOO_SHORT;
    }
    if(!$lastname) {
        $errors['lastname'] = ERROR_REQUIRED;
    } elseif(mb_strlen($lastname) < 5 ) {
        $errors['lastname'] = ERROR_TOO_SHORT;
    }
    if(!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_INVALID_EMAIL;
    }
    if(!$password) {
        $errors['password'] = ERROR_REQUIRED;
    } elseif(mb_strlen($password) < 6 ) {
        $errors['password'] = ERROR_TOO_SHORT;
    }
    if(!$confirmpassword) {
        $errors['confirmpassword'] = ERROR_REQUIRED;
    } elseif($password !== $confirmpassword) {
        $errors['password'] = ERROR_PASSWORD_MISMATCH;
    }

  if (empty(array_filter($errors, fn ($e) => $e !== ''))) {

    $statement = $pdo->prepare('INSERT INTO user VALUES (
    DEFAULT,
    :firstname,
    :lastname,
    :email,
    :password
    )');
    $hashedpassword = password_hash($password, PASSWORD_ARGON2I);
    $statement->bindValue(':firstname', $firstname);
    $statement->bindValue(':lastname', $lastname);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $hashedpassword);
    $statement->execute();
    header('Location: /');
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <?php require_once 'includes/head.php' ?>
  <link rel="stylesheet" href="/public/css/auth-register.css">
  <title>Inscription</title>
</head>

<body>
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
    <div class="block p-20 form-container">
        <h1>Inscription</h1>
        <form action="/auth-register.php" , method="POST">
          <div class="form-control">
            <label for="firstname">Prénom</label>
            <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? '' ?>">
            <?php if ($errors['firstname']) : ?>
              <p class="text-danger"><?= $errors['firstname'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-control">
            <label for="lastname">Nom</label>
            <input type="text" name="lastname" id="lastname" value="<?= $lastname ?? '' ?>">
            <?php if ($errors['lastname']) : ?>
              <p class="text-danger"><?= $errors['lastname'] ?></p>
            <?php endif; ?>
          </div>
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
          <div class="form-control">
            <label for="confirmpassword">Confirmation de mot de passe</label>
            <input type="text" name="confirmpassword" id="confirmpassword">
            <?php if ($errors['confirmpassword']) : ?>
              <p class="text-danger"><?= $errors['confirmpassword'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-actions">
            <a href="/" class="btn btn-secondary" type="button">Annuler</a>
            <button class="btn btn-primary" type="submit">Valider</button>
          </div>
        </form>
      </div>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>

</body>

</html>

