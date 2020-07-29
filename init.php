<?php
session_start();

$connect = mysqli_connect('localhost', 'php', 'password', 'yeti_cave');

if (!$connect) {
    exit('Ошибка подключения: ' . mysqli_connect_error());
}

mysqli_set_charset($connect, 'utf8mb4');
