
<?php
require_once('helpers.php');
require_once('init.php');
require_once('validators.php');
require_once('const.php');

$categories = get_categories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$id  = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$result_lot = get_lot($connect, $id);

function insert_bet($connect, $cost, $lot_id, $user_id) {
    $sql = "INSERT INTO bets (user_id, lot_id, price) VALUES (" . $user_id . "," . $lot_id . "," . $cost . ")";
    $res = mysqli_query($connect, $sql);

    if ($res) {
        header('Location: lot.php?id=' . $lot_id);
    }
}

if (!mysqli_num_rows($result_lot)) {
    http_response_code(UNKNOWN_ERROR);
    $page_content = include_template('error.php',
        [
            'categories_content' => $categories_content,
            'error_code' => UNKNOWN_ERROR,
            'error_text' => 'Страница не найдена',
            'error_description' => 'Данной страницы не существует на сайте.'
        ]
    );
    $title = 'Error';
} else {
    $lot = mysqli_fetch_assoc($result_lot);

    $title = $lot['lot_name'] ?? 'Лот';

    $lot_bets = get_lot_bets($connect, $lot['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'], $_SESSION['user']['id'])) {
        $errors = [];

        $bet = filter_input_array(INPUT_POST, [
            'cost' => FILTER_DEFAULT
        ], true);

        $rules = [
            'cost' => function($value, $price, $bet_step) {
                return validate_bet((int)$value, (int)$price, (int)$bet_step);
            }
        ];

        foreach ($bet as $key => $value) {
            $bet[$key] = trim($value);
            $errors[$key] = validate_filled($value);
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule($value, $lot['price'], $lot['bet_step']);
            }
        }

        $errors = array_filter($errors);

        if (!count($errors)) {
            insert_bet($connect, intval($bet['cost']), $lot['id'], $_SESSION['user']['id']);
        }
    }

    $page_content = include_template('lot.php',
        [
            'lot' => $lot,
            'categories_content' => $categories_content,
            'errors' => !empty($errors) ? $errors : null,
            'lot_bets' => $lot_bets ?? null
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
