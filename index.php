<?php

require_once 'vendor/autoload.php';

$app = new \Slim\App();
$view = new \Slim\Views\PhpRenderer('./template/html');

//$app->get('/', function($request, $response) {
//   return $response->write('Hello World');
//});

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

$app->get('/', function($request, $response) use ($app, $view) {
    return $view->render($response, '/index.html');
});

$app->get('/login', function($request, $response) use ($app, $view) {
    return $view->render($response, '/login.html');
});

$app->run();
