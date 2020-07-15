<?php
require_once('auth.php');
require_once('helpers.php');
require_once('init.php');
require_once('validators.php');

$categories = getCategories($connect);

$categories_content  = include_template('categories.php',
    [
        'categories' => $categories
    ]
);

$title = 'Регистрация';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;
    $errors = [];

    $user = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'contacts' => FILTER_DEFAULT,
    ], true);

    foreach ($_POST as $key => $value) {
        $errors[$key] = validateFilled(trim($value));
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $email = mysqli_real_escape_string($connect, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($connect, $sql);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email';
        } else if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);
            $sql = 'INSERT INTO users (email, name, password, contacts) VALUES (?, ?, ? ,?)';
            $res = mysqli_stmt_execute(db_get_prepare_stmt($connect, $sql, $email, $form['name'], $password, $form['message']));
        }

        if ($res && !count($errors)) {
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
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'title' => $title,
        'categories' => $categories
    ]
);

print($layout_content);
