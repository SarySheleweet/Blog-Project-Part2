<?php

function findArticleById($id): array | null {
    $filename = __DIR__ . '/../data/articles.json';
    if(file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIds = array_column($articles, 'id');
        $articleIndex = array_search($id, $articleIds);
        return $articles[$articleIndex];
    }
    else {
        return null;
    }
}
