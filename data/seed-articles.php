<?php
$articles = json_decode(file_get_contents(__DIR__ . '/articles.json'), true);

$dns = 'mysql:host=localhost;dbname=blog-dyma';
$user = 'root';
$password = '';
$pdo = new PDO ($dns, $user, $password);
$statement = $pdo->prepare('INSERT INTO article (title, category, content, image) VALUE (:title, :category, :content, :image)');
foreach ($articles as $article) {
    $statement->bindValue(':title', $article['title']);
    $statement->bindValue(':category', $article['category']);
    $statement->bindValue(':content', $article['content']);
    $statement->bindValue(':image', $article['image']);
    $statement->execute();
}