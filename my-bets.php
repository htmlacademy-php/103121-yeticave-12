<?php
require_once('helpers.php');
require_once('init.php');
require_once('const.php');

$categories = get_categories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

if (!isset($_SESSION['user'])) {
    http_response_code(FORBIDDEN_ERROR);
    $title = 'Error';
    $page_content = include_template('error.php',
        [
            'categories_content' => $categories_content,
            'error_code' => FORBIDDEN_ERROR,
            'error_text' => 'Доступ запрещен',
            'error_description' => 'Для получения доступа авторизуйтесь или зарегистрируйтесь'
        ]
    );
} else {
    $title = 'Мои ставки';

    $bets = get_user_bets($connect, $_SESSION['user']['id']);

    $page_content = include_template('my-bets.php',
        [
            'categories_content' => $categories_content,
            'bets' => $bets ?? null
        ]
    );
}

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'title' => $title,
        'categories' => $categories,
        'user' => $_SESSION['user'] ?? null
    ]
);

print($layout_content);
