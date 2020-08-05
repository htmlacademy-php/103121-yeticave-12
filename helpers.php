<?php
require_once('const.php');
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * @param mysqli $connect
 * @param string $sql
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return mysqli_result
 */

function handle_query(mysqli $connect, string $sql) {
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        exit('Ошибка MySQL: ' . mysqli_error($connect));
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

    $new_price = $new_price < 1000 ? $new_price : number_format($new_price, 0, '', ' ');

    return $new_price . ' ₽';
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
    $hours_count = floor($time_difference / SECONDS_IN_HOUR);
    $minutes_count = floor(($time_difference % SECONDS_IN_HOUR) / SECONDS_IN_MINUTE);

    return [$hours_count, $minutes_count];
}

/**
 * @param string $name
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string
 */

function getPostVal(string $name) {
    return filter_input(INPUT_POST, $name);
}

/**
 * @param mysqli $connect
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return mixed
 */

function getCategories(mysqli $connect) {
    $sql_get_categories = 'SELECT * FROM categories;';
    $result_categories = handle_query($connect, $sql_get_categories);
    return mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
}

/**
 * @param mysqli $connect
 * @param int $lot_id
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return mixed
 */

function getLotBets(mysqli $connect, int $lot_id) {
    $sql_get_lot_bets = "SELECT u.name, b.price, b.date FROM bets b JOIN users u ON u.id = b.user_id WHERE b.lot_id = '$lot_id' ORDER BY b.date DESC;";
    $result_lot_bets = handle_query($connect, $sql_get_lot_bets);
    return mysqli_fetch_all($result_lot_bets, MYSQLI_ASSOC);
}

/**
 * @param mysqli $connect
 * @param int $user_id
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return mixed
 */

function getUserBets(mysqli $connect, int $user_id) {
    $sql_get_user_bets = "SELECT l.id, l.name AS lot_name, l.image, c.name AS category_name, l.finish_date, u.contacts, MAX(b.price) AS bet_price, MAX(b.date) AS bet_date
        FROM bets b
        JOIN users u ON u.id = b.user_id
        JOIN lots l ON b.lot_id = l.id
        JOIN categories c ON c.id = l.category_id
        WHERE u.id = '$user_id'
        GROUP BY l.id";
    $result_user_bets = handle_query($connect, $sql_get_user_bets);
    return mysqli_fetch_all($result_user_bets, MYSQLI_ASSOC);
}

function getLot(mysqli $connect, $id) {
    $sql_get_lot = "SELECT l.name AS lot_name,
        l.id,
        c.name AS category_name,
        l.image,
        l.description,
        MAX(IFNULL(b.price, l.start_price)) AS price,
        l.finish_date,
        l.bet_step,
        l.author_id,
        (SELECT user_id FROM bets WHERE date = (SELECT MAX(date) FROM bets b JOIN lots l ON l.id = b.lot_id WHERE l.id = '$id')) AS bet_author_id
        FROM lots l JOIN categories c ON l.category_id = c.id
        LEFT JOIN bets b ON l.id = b.lot_id
        WHERE l.id = '$id'";
    return handle_query($connect, $sql_get_lot);
}

/**
 * @param string $date
 *
 * @author Trikashnyi Artem tema-luch@mail.ru
 *
 * @return string
 */

function getTimePassed(string $date) {
    $time_passed = time() - strtotime($date);
    $hours = floor($time_passed / SECONDS_IN_HOUR);
    $minutes = floor(($time_passed % SECONDS_IN_MINUTE) / 60);
    switch (true) {
        case (($hours < 1) && ($minutes < 1)):
            return 'Сейчас';

        case ($hours < 1):
            return $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' назад';

        case ($hours < 23):
            return $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';

        default:
            $bet_time = strtotime($date);
            return date('d.m.y', $bet_time) . ' в ' . date('H:i', $bet_time);
    }
}

