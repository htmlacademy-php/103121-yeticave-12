<?php
require_once('helpers.php');
require_once('init.php');
require_once('getwinner.php');

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$sql_get_lots = 'SELECT l.id,
l.name,
l.start_price,
l.image,
MAX(IFNULL(b.price, l.start_price)) AS price,
c.name AS category,
l.finish_date
FROM lots l
LEFT JOIN bets b ON l.id = b.lot_id
JOIN categories c ON l.category_id = c.id
WHERE UNIX_TIMESTAMP(l.finish_date) > UNIX_TIMESTAMP()
GROUP BY l.id
ORDER BY l.start_date DESC;';

$result_lots = handle_query($connect, $sql_get_lots);

$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$page_content = include_template('main.php',
    [
        'lots' => $lots,
        'categories' => $categories,
        'categories_content' => $categories_content
    ]
);

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'title' => 'Yeti-cave',
        'categories' => $categories,
        'user' => $_SESSION['user'] ?? null
    ]
);

print($layout_content);
