<?php
require_once('helpers.php');
require_once('init.php');

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$search = $_GET['search'] ?? null;

if ($search) {
    $current_page = $_GET['page'] ?? 1;
    $page_items = 1;

    $offset = ($current_page - 1) * $page_items;

    $sql = 'SELECT COUNT(l.id) AS cnt
        FROM lots l
        WHERE UNIX_TIMESTAMP(l.finish_date) > UNIX_TIMESTAMP()
        AND MATCH(l.name, l.description) AGAINST(?)
        ORDER BY l.start_date DESC';

    $stmt = db_get_prepare_stmt($connect, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $items_count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['cnt'];

    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    $sql = 'SELECT l.id,
        l.name,
        l.start_price,
        l.image,
        IFNULL(b.price, l.start_price) AS price,
        c.name AS category,
        l.finish_date
        FROM lots l
        LEFT JOIN bets b ON l.id = b.lot_id
        JOIN categories c ON l.category_id = c.id
        WHERE UNIX_TIMESTAMP(l.finish_date) > UNIX_TIMESTAMP()
        AND MATCH(l.name, l.description) AGAINST(?)
        ORDER BY l.start_date DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($connect, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

$page_content = include_template('search.php',
    [
        'categories_content' => $categories_content,
        'categories' => $categories,
        'search' => $search,
        'lots' => $lots ?? null,
        'pages' => $pages ?? null,
        'pages_count' => $pages_count ?? null,
        'current_page' => (int)$current_page ?? null
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
