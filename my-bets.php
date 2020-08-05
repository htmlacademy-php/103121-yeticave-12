<?php
require_once('helpers.php');
require_once('init.php');
require_once('const.php');

if (!isset($_SESSION['user'])) {
    http_response_code(FORBIDDEN_ERROR);
    exit();
}

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$bets = getUserBets($connect, $_SESSION['user']['id']);

$page_content = include_template('my-bets.php',
    [
        'categories_content' => $categories_content,
        'bets' => $bets ?? null
    ]
);

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'title' => 'Мои ставки',
        'categories' => $categories,
        'user' => $_SESSION['user'] ?? null
    ]
);

print($layout_content);
