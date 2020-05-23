<?php
require_once('helpers.php');
require_once('init.php');

$is_auth = rand(0, 1);

$user_name = 'Артём';

$title = 'Yeti-cave';

$sql_lots = 'SELECT l.name,
l.start_price,
l.image,
IFNULL(b.price, l.start_price) AS price,
c.name AS category,
l.finish_date
FROM lots l
LEFT JOIN bets b ON l.id = b.lot_id
JOIN categories c ON l.category_id = c.id
WHERE UNIX_TIMESTAMP(l.finish_date) > UNIX_TIMESTAMP()
ORDER BY l.start_date DESC;';

$sql_categories = 'SELECT * FROM categories;';

$result_lots = handle_query($connect, $sql_lots);
$result_categories = handle_query($connect, $sql_categories);

$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
$goods = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

/**
 * @param float $old_price
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string
 */

function format_price(float $old_price) {
    $new_price = ceil($old_price);

    $new_price = $new_price < 1000 ? $new_price . ' ₽' : number_format($new_price, 0, '', ' ') . ' ₽';

    return $new_price;
}

$page_content = include_template('main.php',
    [
        'categories' => $categories,
        'goods' => $goods
    ]
);

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $title,
        'categories' => $categories
    ]
);

/**
 * @param string $date
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return int[]
 */

function get_time_range(string $date) {
    $time_difference = strtotime($date) - time();
    $hours_count = floor($time_difference / 3600);
    $minutes_count = floor(($time_difference % 3600) / 60);

    return [$hours_count, $minutes_count];
}

print($layout_content);
