<?php

require_once 'vendor/autoload.php';
require_once 'helper/DbConnection.php';

use function database\getDBConnection as openDatabase;

$app = new \Slim\App();
$view = new \Slim\Views\PhpRenderer('./template/html');

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

function isLoggedIn() {
    session_start();
    $loggedIn = isset($_SESSION['isLoggedIn']);
    var_dump($loggedIn);
    return $loggedIn;
}

function createUserSession($userId, $username) {
//    session_start();
    $_SESSION['userid'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['isLoggedIn'] = true;
}

function deleteUserSession() {
    session_unset();
    session_destroy();
}

function getUserId($request) {
    session_start();
    if(isLoggedIn() && isset($_SESSION["userid"]) && !empty($_SESSION["userid"])) {
        return $_SESSION["userid"];
    } else {
        return false;
    }
}

function login($username, $password) {
    $db = openDatabase();
    $userSelect = $db->prepare('SELECT UserId, PasswordHash, SaltValue FROM Users WHERE Username = :username');
    $userSelect->bindParam(':username', $username);

    if (!$userSelect->execute()) {
        throw new Exception("There appears to be a problem with the database connection");
    }

    $user = $userSelect->fetch(PDO::FETCH_ASSOC);

    if (empty($user)) {
        return false;
    }

    $salt = $user['SaltValue'];
    $passwordHash = $user['PasswordHash'];

    $submittedPasswordHash = md5($password . $salt);

    if ($passwordHash == $submittedPasswordHash) {
        return $user['UserId'];
    } else {
        return false;
    }
}

function getUserName($userId) {
    $db = openDatabase();
    $selectUser = $db->prepare('SELECT Username FROM Users WHERE UserId = :userId');
    $selectUser->bindParam(':userId', $userId);

    if (!$selectUser->execute()) {
        throw new Exception("There appears to be a problem with the database connection");
    }
    $user = $selectUser->fetch(PDO::FETCH_ASSOC);
    return $user['Username'];
}

// enabling CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/', function($request, $response) use ($app, $view) {
//    if (!isLoggedIn()) {
//        return $response->withRedirect('/login');
//    }
    return $view->render($response, '/index.html');
});

$app->get('/login', function($request, $response) use ($app, $view) {
//    if (isLoggedIn()) {
//        return $response->withRedirect('/');
//    }
    return $view->render($response, '/login.html');
});

$app->post('/login', function($request, $response) use ($app) {
    if(isLoggedIn()) {
        return $response->withRedirect('/');
    }
    $params = $request->getParsedBody();
    $username = $params['username'];
    $password = $params['password'];

    $userId = login($username, $password);

    if($userId == false) {
        return $response->withJson(json_encode(false));
    } else {
        createUserSession($userId, $username);
        $json = array('username' => getUserName($userId));
        return $response->withJson($json);
    }
});

$app->get('/logout', function($request, $response) use ($app) {
    deleteUserSession();
    $response = FigResponseCookies::expire($response, 'ahoy_cookie');
    return $response->withRedirect('/');
});

$app->run();
