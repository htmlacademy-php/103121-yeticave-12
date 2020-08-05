
<?php
require_once('helpers.php');
require_once('init.php');
require_once('validators.php');

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$id  = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$result_lot = getLot($connect, $id);

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

    $title = $lot['lot_name'];

    $lot_bets = getLotBets($connect, $lot['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SESSION['user'])) {
            $errors = [];

            $bet = filter_input_array(INPUT_POST, [
                'cost' => FILTER_DEFAULT
            ], true);

            $rules = [
                'cost' => function($value, $price, $bet_step) {
                    return validateBet((int)$value, $price, $bet_step);
                }
            ];

            foreach ($bet as $key => $value) {
                $bet[$key] = trim($value);
                $errors[$key] = validateFilled($value);
                if (isset($rules[$key])) {
                    $rule = $rules[$key];
                    $errors[$key] = $rule($value, $lot['price'], $lot['bet_step']);
                }
            }

            $errors = array_filter($errors);

            if (!count($errors)) {
                $bet_data = intval($bet['cost']);
                $sql = "INSERT INTO bets (user_id, lot_id, price) VALUES (" . $_SESSION['user']['id'] . "," . $lot['id'] . "," . $bet_data . ")";
                $res = mysqli_query($connect, $sql);

                if ($res) {
                    header('Location: lot.php?id=' . $lot['id']);
                }
            }
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
