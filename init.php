<?php
$connect = mysqli_connect('localhost', 'php', 'password', 'yeti_cave');

if (!$connect) {
    exit('Ошибка подключения: ' . mysqli_connect_error());
}

mysqli_set_charset($connect, 'utf8mb4');

/**
 * @param object $connect
 * @param string $sql
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return object
 */

function handle_query(object $connect, string $sql) {
    $statement = mysqli_prepare($connect, $sql);
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        exit("Ошибка MySQL: " . mysqli_error($connect));
    }

    return $result;
}
