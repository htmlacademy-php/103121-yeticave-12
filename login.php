<?php
require_once('helpers.php');
require_once('init.php');
require_once('validators.php');

if (isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
}

$categories = get_categories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $user = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT
    ], true);

    foreach ($user as &$item) {
        $item = trim($item);
    }

    foreach ($_POST as $key => $value) {
        $errors[$key] = validate_filled($value);
    }

    if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $email = mysqli_real_escape_string($connect, $user['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($connect, $sql);

        $user_db = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if ($user_db) {
            password_verify($user['password'], $user_db['password']) ?
                $_SESSION['user'] = $user_db :
                $errors['password'] = 'Неверный пароль';
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }

        if (!count($errors)) {
            header('Location: index.php');
            exit();
        }
    }
}

$page_content = include_template('login.php',
    [
        'categories_content' => $categories_content,
        'categories' => $categories,
        'errors' => !empty($errors) ? $errors : null
    ]
);

$layout_content = include_template('layout.php',
    [
        'content' => $page_content,
        'title' => 'Авторизация',
        'categories' => $categories,
        'user' => $_SESSION['user'] ?? null
    ]
);

print($layout_content);
