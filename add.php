<?php
require_once('auth.php');
require_once('helpers.php');
require_once('init.php');
require_once('validators.php');

$categories = getCategories($connect);

$categories_ids = array_column($categories, 'id');

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$title = 'Добавление лота';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $rules = [
        'category' => function($value) use ($categories_ids) {
            return validateCategory($value, $categories_ids);
        },
        'lot-step' => function($value) {
            return validateInt((int)$value);
        },
        'lot-rate' => function($value) {
            return validateFloat((float)$value);
        },
        'lot-date' => function($value) {
            return validateDate($value);
        }
    ];

    $lot = filter_input_array(INPUT_POST, [
            'lot-name' => FILTER_DEFAULT,
            'category' => FILTER_DEFAULT,
            'message' => FILTER_DEFAULT,
            'lot-rate' => FILTER_DEFAULT,
            'lot-step' => FILTER_DEFAULT,
            'lot-date' => FILTER_DEFAULT
        ], true);

    foreach ($_POST as $key => $value) {
        $errors[$key] = validateFilled($value);
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_size = $_FILES['lot-img']['size'];
        $file_error = $_FILES['lot-img']['error'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        $file_type_array = explode('/', $file_type);
        $filepath = 'uploads/' . uniqid() . '.' . end($file_type_array);
        if (!in_array($file_type, ['image/jpg', 'image/jpeg', 'image/png'])) {
            $errors['lot-img'] = 'Загрузите картинку в формате JPG, JPEG, PNG';
        } else if ($file_size > 5000000) {
            $errors['lot-img'] = 'Максимальный размер файла: 5мб';
        } else {
            move_uploaded_file($tmp_name, $filepath);
            $lot['lot-img'] = $filepath;
        }
    } else {
        $errors['lot-img'] = 'Вы не загрузили файл';
    }

    if (!count($errors)) {
        $sql = 'INSERT INTO lots (author_id, name, category_id, description, start_price, bet_step, finish_date, image) VALUES (1, ?, ?, ?, ?, ?, ?, ?)';
        $res = mysqli_stmt_execute(db_get_prepare_stmt($connect, $sql, $lot));

        if ($res) {
            header('Location: lot.php?id=' . mysqli_insert_id($connect));
        }
    }
}

$page_content = include_template('add.php',
    [
        'categories_content' => $categories_content,
        'categories' => $categories,
        'errors' => !empty($errors) ? $errors : null
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

print($layout_content);
