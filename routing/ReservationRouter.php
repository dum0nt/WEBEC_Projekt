<?php

function fillInformation($reservations) {
    $shipDao = new \dao\ShipDAO();
    $userDao = new \dao\UserDAO();

    return array_map(function($reservation) use ($shipDao, $userDao) {
        $res = array();
        $res['reservationId'] = $reservation['ReservationId'];
        $res['startTime'] = $reservation['StartTime'];
        $res['endTime'] = $reservation['EndTime'];
        $res['shipName'] = $shipDao->getShipName($reservation['ShipId']);
        $res['userName'] = $userDao->getUser($reservation['UserId'])['UserName'];
        return $res;
    }, $reservations);
}

$app->get('/reservations', function($request, $response) use ($resDao) {
    $params = $request->getQueryParams();
    if (isset($params['user'])) {
        // get reservations for specific user
        $reservations = array_filter($resDao->getAllReservations(), function($e) use ($params) {
            return $e['UserId'] == intval($params['user']);
        });
        return $response->withJson(array_values(fillInformation($reservations)), 200);
    } else {
        // get all reservations
        $reservations = $resDao->getAllReservations();
        return $response->withJson(array_values(fillInformation($reservations)), 200);
    }
})->add($authenticate);

$app->post('/reservations', function($request, $response) use ($resDao, $shipDao, $userDao) {
    $json = $request->getParsedBody();
    $resId = $resDao->createReservation($json);
    if ($resId < 0) {
        return $response->write('Bad request.')->withStatus(400);
    } else {
        $result = array();
        $result['reservationId'] = $resId;
        $result['startTime'] = $json['startTime'];
        $result['endTime'] = $json['endTime'];
        $result['userName'] = $userDao->getUser($json['userId'])['UserName'];
        $result['shipName'] = $shipDao->getShipName($json['shipId']);
        return $response->withJson($result, 201);
    }
})->add($authenticate);

$app->delete('/reservations/{id}', function($request, $response, $args) use ($resDao) {
    $resId = intval($args['id']);
    if (!$resDao->exists($resId)) {
        return $response->write('User not found')->withStatus(404);
    }

    if ($resDao->deleteReservation($resId)) {
        return $response->withStatus(204);
    } else {
        return $response->write('Server error')->withStatus(500);
    }
})->add($authenticate);