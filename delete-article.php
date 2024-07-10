<?php
require_once __DIR__ .'/database/database.php';
require_once __DIR__. '/./utils/findArticleById.php';
$articleDB = require_once __DIR__. '/./database/models/ArticleDb.php';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if(!$id) {
    header('Location: /');
} else {
        $articleDB->deleteArticle($id);
}
header('Location: /');
?>
