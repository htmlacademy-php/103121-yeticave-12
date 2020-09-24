<?php
require_once('helpers.php');
require_once('init.php');

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT) ?? null;
$type = 'category';

if ($category) {
    $current_page = $_GET['page'] ?? 1;

    $pagination = getPagination($connect, $current_page, $category, $type);

    $lots = getLotsBySearch($connect, $type, $pagination['search_escaped'], $pagination['offset']);
    $category_name = mysqli_fetch_assoc(getCategoryById($connect, $category));
}

$pagination_content = include_template('pagination.php',
    [
        'pages' => $pagination['pages'] ?? null,
        'pages_count' => $pagination['pages_count'] ?? null,
        'current_page' => (int)$current_page ?? null,
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
        'lots' => $lots ?? null
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
