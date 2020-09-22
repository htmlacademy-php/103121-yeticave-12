<?php
require('vendor/autoload.php');

$result_lots_without_winner =  getLotsWithoutWinner($connect);

if (mysqli_num_rows($result_lots_without_winner)) {
    $lots_without_winner = mysqli_fetch_all($result_lots_without_winner, MYSQLI_ASSOC);

    foreach($lots_without_winner as $lot) {
        $result_winner = getWinner($connect, $lot['id']);

        if (mysqli_num_rows($result_winner)) {
            $winner = (int)mysqli_fetch_assoc($result_winner)['user_id'];

            $sql = "UPDATE lots SET winner_id = '$winner' WHERE id = " . $lot['id'];
            $result = mysqli_query($connect, $sql);

            $result_user = mysqli_query($connect, "SELECT email, name FROM users WHERE id = '$winner'");
            $user = mysqli_fetch_assoc($result_user);

            $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
                ->setUsername('keks@phpdemo.ru')
                ->setPassword('htmlacademy')
            ;

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message())
                ->setSubject('Ваша ставка победила')
                ->setFrom(['keks@phpdemo.ru' => 'YetiCave'])
                ->setTo([$user['email'] => $user['name']])
            ;

            $msg_content = include_template('email.php',
                [
                    'user' => $user,
                    'lot' => $lot,
                    'host' => $_SERVER['HTTP_HOST']
                ]
            );
            $message->setBody($msg_content, 'text/html');

            $result = $mailer->send($message);
        }
    }
}
