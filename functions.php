<?php
/**
 * @param object $connect
 * @param string $sql
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return mysqli_result
 */

function handle_query(mysqli $connect, string $sql) {
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        exit("Ошибка MySQL: " . mysqli_error($connect));
    }

    return $result;
}

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
