
<?php
require_once('helpers.php');
require_once('init.php');

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$sql_get_lot = 'SELECT l.name AS lot_name,
c.name AS category_name,
l.image,
l.description,
IFNULL(b.price, l.start_price) AS price,
l.finish_date,
l.bet_step
FROM lots l JOIN categories c ON l.category_id = c.id
LEFT JOIN bets b ON l.id = b.lot_id
WHERE l.id = ' . $id;

$result_lot = handle_query($connect, $sql_get_lot);

if (!mysqli_num_rows($result_lot)) {
    http_response_code(404);
    $page_content = include_template('error.php',
        [
            'categories_content' => $categories_content
        ]
    );
    $title = 'Error';
} else {
    $lot = mysqli_fetch_assoc($result_lot);

    $page_content = include_template('lot.php',
        [
            'lot' => $lot,
            'categories_content' => $categories_content
        ]
    );
    $title = $lot['lot_name'];
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
