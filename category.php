<?php
require_once('helpers.php');
require_once('init.php');

$categories = get_categories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$category = (int)filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT) ?? null;
$type = 'category';
$pagination = null;
$lots = null;
$category_name = null;

if ($category) {
    $pagination = get_pagination($connect, (string)$category, $type);

    $lots = get_lots_by_search($connect, $type, $pagination['search_escaped'] ?? '', $pagination['offset'] ?? 1);
    $category_name = mysqli_fetch_assoc(get_category_by_id($connect, $category));
}

$pagination_content = include_template('pagination.php',
    [
        'pages' => $pagination['pages'] ?? null,
        'pages_count' => $pagination['pages_count'] ?? null,
        'current_page' => $pagination['current_page'] ?? null,
        'search' => $category,
        'type' => 'category'
    ]
);

$page_content = include_template('category.php',
    [
        'categories_content' => $categories_content,
        'pagination_content' => $pagination_content,
        'categories' => $categories,
        'category' =>  $category_name,
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
