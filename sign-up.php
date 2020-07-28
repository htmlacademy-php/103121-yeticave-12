<?php
require_once('helpers.php');
require_once('init.php');
require_once('validators.php');

if (isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $user = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'message' => FILTER_DEFAULT,
    ], true);

    foreach ($user as &$item) {
        $item = trim($item);
    }

    foreach ($_POST as $key => $value) {
        $errors[$key] = validateFilled($value);
    }

    if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $email = mysqli_real_escape_string($connect, $user['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($connect, $sql);

        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($user['password'], PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (email, name, password, contacts) VALUES (?, ?, ? ,?)';
            $res = mysqli_stmt_execute(db_get_prepare_stmt($connect, $sql, [$email, $user['name'], $password, $user['message']]));
        }

        if (!count($errors)) {
            header('Location: index.php');
            exit();
        }
    }
}

$page_content = include_template('sign-up.php',
    [
        'categories_content' => $categories_content,
        'categories' => $categories,
        'errors' => !empty($errors) ? $errors : null
    ]
);

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'title' => 'Регистрация',
        'categories' => $categories
    ]
);

print($layout_content);
