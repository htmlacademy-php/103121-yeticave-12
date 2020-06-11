<?php
require_once('init.php');
require_once('functions.php');

$sql_get_categories = 'SELECT * FROM categories;';
$result_categories = handle_query($connect, $sql_get_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
