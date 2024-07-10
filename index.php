<?php
require_once __DIR__. '/database/database.php';
require_once __DIR__. '/database/security.php';

$currentUser = isLoggedIn();
$articleDB = require_once __DIR__. '/./database/models/ArticleDb.php';


$articles = $articleDB->fetchAllArticles();
$categories = [];


$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? [];

if (count($articles)) {
  
  $categories1 = array_map(fn($e) => $e['category'], $articles);
  $categories = array_reduce($categories1, function ($acc, $e) {
    if(isset($acc[$e])) {
      $acc[$e]++;
    } else {
      $acc[$e] = 1;
    }
    return $acc;
  }, []);
  $articlePerCategories = array_reduce($articles, function ($acc, $article) {
    if (isset($acc[$article['category']])) {
      $acc[$article['category']][] = $article;
    } else {
      $acc[$article['category']] = [$article];
    }
    return $acc;
  }, []);
}

// print_r(array_unique($categories1));
// Array ( [0] => nature [1] => nature [2] => nature [3] => politics [4] => politics [5] => politics [6] => technology [7] => technology [8] => technology )
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <?php require_once 'includes/head.php' ?>
  <link rel="stylesheet" href="/public/css/index.css">
  <title>Blog</title>
</head>

<body>
  <div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
      <div class="newsfeed-container">
        <div class="category-container">
          <ul class="category-container">
            <li class=<?= $selectedCat ? '' : 'cat-active' ?>>
              <a href="/">Tous les articles <span class="small">(<?= count($articles) ?>)</span></a>
            </li>
            <?php foreach ($categories as $catName => $catNum) : ?>
              <li class=<?= $selectedCat ===  $catName ? 'cat-active' : '' ?>>
                <a href="/?cat=<?= $catName ?>"> <?= mb_convert_case($catName, MB_CASE_TITLE) ?><span class="small">(<?= $catNum ?>)</span> </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="newsfeed-content">
          <?php if (!$selectedCat) : ?>
            <?php foreach ($categories as $cat => $num) : ?>
              <h2><?= $cat ?></h2>
              <div class="articles-container">
                <?php foreach ($articlePerCategories[$cat] as $a) : ?>
                  <a href="/show-article.php?id=<?= $a['id'] ?>" class="article block">
                    <div class="overflow">
                      <div class="img-container" style="background-image:url(<?= $a['image'] ?>"></div>
                    </div>
                    <h3><?= $a['title'] ?></h3>
                    <?php if($a['author']) : ?>
                      <div class="article-author">
                        <p> <?= $a['firstname'] . ' ' . $a['lastname'] ?></p>
                      </div>
                    <?php endif; ?>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <h2><?= $selectedCat ?></h2>
            <div class="articles-container">
              <?php foreach ($articlePerCategories[$selectedCat] as $a) : ?>
                <a href="/show-article.php?id=<?= $a['id'] ?>" class="article block">
                  <div class="overflow">
                    <div class="img-container" style="background-image:url(<?= $a['image'] ?>"></div>
                  </div>
                  <h3><?= $a['title'] ?></h3>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
  </div>

</body>

</html>

