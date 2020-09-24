<?php

/**
 * @param string $name
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validate_filled(string $name) {
    return (empty($name)) ? 'Это поле должно быть заполнено' : null;
}

/**
 * @param int $value
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validate_int(int $value) {
    return ($value <= 0) ? 'В этом поле должно быть целое положительное число' : null;
}

/**
 * @param float $value
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validate_float(float $value) {
    return ($value <= 0) ? 'В этом поле должно быть положительное число' : null;
}

/**
 * @param string $id
 * @param string[] $allowed_list
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validate_category(string $id, array $allowed_list) {
    return (!in_array($id, $allowed_list)) ? 'Указана несуществующая категория' : null;
}

/**
 * @param string $value
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validate_date(?string $value) {
    if (!is_date_valid($value)) {
        return 'Необходимо ввести дату в формате ГГГГ-ММ-ДД';
    } else if (strtotime($value) < strtotime('+1 day')) {
        return 'Указанная дата должна быть больше текущей даты хотя бы на один день';
    }

    return null;
}

function validate_bet($value, $price, $bet_step) {
    if ($value <= 0) {
        return 'В этом поле должно быть целое положительное число';
    } else if ($value < ($price + $bet_step)) {
        return 'Ставка должна быть больше, чем текущая цена лота + шаг ставки';
    }

    return null;
}
