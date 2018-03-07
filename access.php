<?php

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

session_start();    $access = FALSE;

if (isset($_SESSION['is_logged']) && $_SESSION['logged'] !== FALSE) {

    $access = TRUE;

    $dsn = "mysql:host=localhost;dbname=gamingso_slots_db;charset=utf8";
    $user = 'gamingsoft';
    $pass = 'fyI8Z11LWqm49qRlscEK';
    $db = new PDO($dsn, $user, $pass);

    $sql = 'SELECT * FROM `users`';
    $users = $db->prepare($sql);
    $users->setFetchMode(PDO::FETCH_ASSOC);
    $users->execute();
    $users = $users->fetchAll();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Games</title>
    <style>

        .start-pyramid {
            text-decoration-line: none;
            color: rgb(255, 245, 86);
        }

        .start-neon-staxx {
            text-decoration-line: none;
            color: #5f5f5f;
        }

        .start-twinSpin {
            text-decoration-line: none;
            color: blue;
        }

        hr {
            border-color: #ff0404;
        }

    </style>
</head>
<body>
    <? if ($access === TRUE) { ?>
        <? foreach ($users as $user) { ?>

            <h1><a class="start-neon-staxx" target="_blank" href="sys/launcher.php?gameId=neonstaxx_not_mobile&uid=<?= ($user['uid']) ?>&method=start">Neon-staxx <?= ($user['user_name'] . ' - ' . $user['balance'] . $user['playercurrency']) ?></a></h1>
            <h1><a class="start-pyramid" target="_blank" href="sys/launcher.php?gameId=pyramid_new&uid=<?= ($user['uid']) ?>&method=start">Pyramid <?= ($user['user_name'] . ' - ' . $user['balance'] . $user['playercurrency']) ?></a></h1>
            <h1><a class="start-twinSpin" target="_blank" href="sys/launcher.php?gameId=twinSpin&uid=<?= ($user['uid']) ?>&method=start">TwinSpin <?= ($user['user_name'] . ' - ' . $user['balance'] . $user['playercurrency']) ?></a></h1>
			<h1><a class="start-stickers" target="_blank" href="sys/launcher.php?gameId=stickers_not_mobile&uid=<?= ($user['uid']) ?>&method=start">Stickers <?= ($user['user_name'] . ' - ' . $user['balance'] . $user['playercurrency']) ?></a></h1>

            </br></br><hr>

        <? } ?>
    <? } ?>
</body>
</html>