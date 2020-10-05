<?php
require_once('helpers.php');
require_once('init.php');

$categories = get_categories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$search = $_GET['search'] ?? null;
$type = 'search';
$pagination = null;
$lots = null;

if ($search) {
    $pagination = get_pagination($connect, $search, $type);

    $lots = get_lots_by_search($connect, $type, $pagination['search_escaped'] ?? '', $pagination['offset'] ?? 1);
}

$pagination_content = include_template('pagination.php',
    [
        'pages' => $pagination['pages'] ?? null,
        'pages_count' => $pagination['pages_count'] ?? null,
        'current_page' => $pagination['current_page'] ?? null,
        'search' => $search,
        'type' => 'search'
    ]
);

$page_content = include_template('search.php',
    [
        'categories_content' => $categories_content,
        'pagination_content' => $pagination_content,
        'categories' => $categories,
        'search' => $search,
        'lots' => $lots
    ]
);

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'title' => 'Поиск',
        'categories' => $categories,
        'user' => $_SESSION['user'] ?? null
    ]
);

print($layout_content);
