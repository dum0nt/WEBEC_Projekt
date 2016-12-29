<?php

require 'helper/DbConnection.php';

use function database\getDBConnection as openDatabase;

function getShips($db) {
    $selectShips = $db->prepare('SELECT * FROM ships');

    if ($selectShips->execute()) {
        $ships = $selectShips->fetchAll(PDO::FETCH_ASSOC);
        return $ships;
    } else {
        exit("Something went wrong.");
    }
}

$db = openDatabase('mysql:host=localhost:3306;dbname=ahoy;charset=utf8', 'root', '');
$ships = getShips($db);
$db = null;

header('Content-Type: application/json; charset=utf-8;');
echo(json_encode($ships));