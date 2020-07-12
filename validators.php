<?php

/**
 * @param string $name
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validateFilled(string $name) {
    if (empty($name)) {
        return "Это поле должно быть заполнено";
    }

    return null;
}

/**
 * @param string $value
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validateInt(string $value) {
    if ((int)$value <= 0) {
        return "В этом поле должно быть целое положительное число";
    }

    return null;
}

/**
 * @param string $value
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validateFloat(string $value) {
    if ((float)$value <= 0) {
        return "В этом поле должно быть положительное число";
    }

    return null;
}

/**
 * @param string $id
 * @param string[] $allowed_list
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validateCategory(string $id, array $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

/**
 * @param string $value
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string|null
 */

function validateDate(?string $value) {
    if (!is_date_valid($value)) {
        return "Необходимо ввести дату в формате ГГГГ-ММ-ДД";
    } else if (strtotime($value) < strtotime("+1 day")) {
        return "Указанная дата должна быть больше текущей даты хотя бы на один день";
    }

    return null;
}
