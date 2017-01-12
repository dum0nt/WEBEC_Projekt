<?php

$app->post('/users', function($request, $response) use ($userDao) {
    $jsonReq = $request->getParsedBody();
    $userId = $userDao->createUser($jsonReq);

    if ($userId == false) {
        return $response->write('Bad request')->withStatus(400);
    }

    $result = array();
    $result['userId'] = $userId;
    $result['userName'] = $jsonReq['userName'];
    $result['firstName'] = $jsonReq['firstName'];
    $result['lastName'] = $jsonReq['lastName'];
    $result['address'] = $jsonReq['address'];
    $result['zip'] = $jsonReq['zip'];
    $result['city'] = $jsonReq['city'];
    $result['email'] = $jsonReq['email'];

    return $response->withJson($result, 201);
})->add($authenticate);

$app->get('/users/{id}', function($request, $response, $args) use ($userDao) {
    $userId = intval($args['id']);
    if (!$userDao->exists($userId)) {
        return $response->write('User not found')->withStatus(404);
    }

    $user = $userDao->getUser($userId);

    $result = array();
    $result['userId'] = $userId;
    $result['userName'] = $user['UserName'];
    $result['firstName'] = $user['FirstName'];
    $result['lastName'] = $user['LastName'];
    $result['address'] = $user['Address'];
    $result['zip'] = $user['ZIP'];
    $result['city'] = $user['City'];
    $result['email'] = $user['Email'];

    return $response->withJson($result, 200);
})->add($authenticate);

$app->put('/users/{id}', function($request, $response, $args) use ($userDao) {
    $userId = intval($args['id']);
    $jsonReq = $request->getParsedBody();
    if (!$userDao->exists($userId)) {
        return $response->write('User not found')->withStatus(404);
    }

    $jsonReq['userId'] = $userId;
    if ($userDao->updateUserPassword($jsonReq)) {
        $user = $userDao->getUser($userId);

        $result = array();
        $result['userId'] = $userId;
        $result['userName'] = $user['UserName'];
        $result['firstName'] = $user['FirstName'];
        $result['lastName'] = $user['LastName'];
        $result['address'] = $user['Address'];
        $result['zip'] = $user['ZIP'];
        $result['city'] = $user['City'];
        $result['email'] = $user['Email'];

        return $response->withJson($result, 200);
    } else {
        return $response->write('Bad request.')->withStatus(400);
    }
})->add($authenticate);

$app->delete('/users/{id}', function($request, $response, $args) use ($userDao) {
    $userId = intval($args['id']);
    if (!$userDao->exists($userId)) {
        return $response->write('User not found')->withStatus(404);
    }

    if ($userDao->deleteUser($userId)) {
        return $response->withStatus(204);
    } else {
        return $response->write('Server error')->withStatus(500);
    }
})->add($authenticate);
