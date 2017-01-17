<?php
session_start();

require_once 'vendor/autoload.php';
use function database\getDBConnection as openDatabase;

$app = new \Slim\App();
$view = new \Slim\Views\PhpRenderer('./template/html');

// Middleware for authentication
$authenticate = function($request, $response, $next) {
    if (!isset($_SESSION['username'])) {
        return $response->write('Authorization required.')->withStatus(401);
    }

    $response = $next($request, $response);
    return $response;
};

require_once 'helper/BerthDAO.php';
require_once 'helper/BerthTownDAO.php';
require_once 'helper/ReservationDAO.php';
require_once 'helper/ShipDAO.php';
require_once 'helper/UserDAO.php';

$berthDao = new BerthDAO();
$berthTownDao = new BerthTownDAO();
$resDao = new ReservationDAO();
$shipDao = new ShipDAO();
$userDao = new UserDAO();

require_once 'routing/BerthRouter.php';
require_once 'routing/BerthTownRouter.php';
require_once 'routing/ReservationRouter.php';
require_once 'routing/ShipRouter.php';
require_once 'routing/UserRouter.php';

/**
 * Processes the login of a user.
 * @param $username: login username
 * @param $password: login password
 * @return bool|int: user ID if the login was successful, false if username or password was wrong
 * @throws Exception: if there is a database error
 */
function login($username, $password) {
    $db = openDatabase();
    $userSelect = $db->prepare('SELECT UserId, PasswordHash, SaltValue FROM users WHERE UserName = :username');
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
        $_SESSION['username'] = $username;
        return intval($user['UserId']);
    } else {
        return false;
    }
}

$app->get('/', function($request, $response) use ($view) {
    return $view->render($response, '/index.html');
});

$app->post('/login', function($request, $response) use($userDao) {
    $params = $request->getParsedBody();
    $username = $params['username'];
    $password = $params['password'];
    $userId = login($username, $password);

    if($userId == false) {
        return $response->write('Wrong password or user.')->withStatus(401);
    } else {
        $json = array('username' => $userDao->getUserName($userId), 'userid' => $userId);
        return $response->withJson($json);
    }
});

$app->get('/logout', function($request, $response) {
    unset($_SESSION['username']);
})->add($authenticate);

$app->run();
