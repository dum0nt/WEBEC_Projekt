<?php

require_once 'vendor/autoload.php';

$app = new \Slim\App();
$app->get('/', function($request, $response) {
    $salt = uniqid();
    $password = 'password';
    $answer = 'Salt: ' . $salt . "\n" . 'Password: ' . $password . "\n" . 'Password hash: ' . md5($password . $salt);
    return $response->write($answer);
});

$app->get('/hello', function($request, $response) {
    $params = $request->getQueryParams();
    if (isset($params['name'])) {
        return $response->write("Hello " . $params['name']);
    } else {
        return $response->write("Hello World");
    }
});

require_once 'helper/BerthDAO.php';
require_once 'helper/BerthTownDAO.php';
require_once 'helper/ReservationDAO.php';
require_once 'helper/ShipDAO.php';
require_once 'helper/UserDAO.php';

$berthDao = new \dao\BerthDAO();
$berthTownDao = new \dao\BerthTownDAO();
$resDao = new \dao\ReservationDAO();
$shipDao = new \dao\ShipDAO();
$userDao = new \dao\UserDAO();

require_once 'routing/BerthRouter.php';
require_once 'routing/BerthTownRouter.php';
require_once 'routing/ReservationRouter.php';
require_once 'routing/ShipRouter.php';
require_once 'routing/UserRouter.php';

$app->run();
