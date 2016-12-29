<?php

require 'helper/DbConnection.php';
require 'vendor/autoload.php';

use function database\getDBConnection as openDatabase;

$app = new \Slim\App();
$app->get('/hello/{name}', function($request, $response, $args) {
   return $response->write("Hello {$args['name']}");
});

function getShips($db) {
    $selectShips = $db->prepare('SELECT * FROM ships');

    if ($selectShips->execute()) {
        $ships = $selectShips->fetchAll(PDO::FETCH_ASSOC);
        return $ships;
    } else {
        exit("Something went wrong.");
    }
}

$app->run();

$db = openDatabase('mysql:host=localhost:3306;dbname=ahoy;charset=utf8', 'root', '');
$ships = getShips($db);
$db = null;

header('Content-Type: application/json; charset=utf-8;');
echo(json_encode($ships));