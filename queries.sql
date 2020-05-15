USE yeti_cave;

INSERT INTO categories (name, code) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

INSERT INTO users (email, name, password, contacts) VALUES
('keks@mail.ru', 'Кекс', 'keksrulez', 'Кошкин дом'),
('lenin@yandex.ru', 'Ленин', 'ussrforever', 'Мавзолей');

INSERT INTO lots (author_id, category_id, name, description, image, start_price, finish_date, bet_step) VALUES
(1, 1, '2014 Rossignol District Snowboard', 'Крутой сноуборд', 'img/lot-1.jpg', 10999, '2020-06-12', 50),
(1, 1, 'DC Ply Mens 2016/2017 Snowboard', 'Чоткий сноуборд', 'img/lot-2.jpg', 159999, '2020-09-03', 100),
(1, 2, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Надежные крепленияя', 'img/lot-3.jpg', 8000, '2020-05-15', 10),
(2, 3, 'Ботинки для сноуборда DC Mutiny Charocal', 'Красивые ботинки', 'img/lot-4.jpg', 10999, '2020-07-01', 150),
(2, 4, 'Куртка для сноуборда DC Mutiny Charocal', 'Теплая куртка', 'img/lot-5.jpg', 7500, '2020-05-29', 90),
(2, 6, 'Маска Oakley Canopy', 'Антикоронавирусная маска', 'img/lot-6.jpg', 5400, '2020-05-20', 30);

INSERT INTO bets (user_id, lot_id, price) VALUES
(2, 1, 15000),
(1, 4, 15000);

SELECT name
FROM categories;

SELECT l.name, l.start_price, l.image, b.price, c.name
FROM lots l JOIN bets b ON l.id = b.lot_id
JOIN categories c ON l.category_id = c.id
WHERE UNIX_TIMESTAMP(l.finish_date) < CURRENT_TIMESTAMP()
ORDER BY l.start_date DESC
LIMIT 3;

SELECT l.name, c.name
FROM lots l JOIN categories c ON l.category_id = c.id
WHERE l.id = 1;

UPDATE lots
SET name = 'Burton Ply Mens 2016/2017 Snowboard'
WHERE id = 2;

SELECT b.id
FROM bets b JOIN lots l ON b.lot_id = l.id
WHERE l.id = 1
ORDER BY b.date ASC;
