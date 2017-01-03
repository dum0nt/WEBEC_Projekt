<?php

$app->get('/reservations', function($request, $response) use ($resDao) {
    $params = $request->getQueryParams();
    if (isset($params['user'])) {
        // get reservations for specific user
        $reservations = array_filter($resDao->getAllReservations(), function($e) use ($params) {
            return intval($e['UserId']) == intval($params['user']);
        });
        return $response->withJson($reservations, 200);
    } else {
        // get all reservations
        $reservations = $resDao->getAllReservations();
        return $response->withJson($reservations, 200);
    }
});

$app->post('/reservations', function($request, $response) use ($resDao) {
    $json = $request->getParsedBody();
    $resId = $resDao->createReservation($json);
    if ($resId < 0) {
        return $response->withStatus(400);
    } else {
        $json['reservationId'] = $resId;
        return $response->withJson($json, 201);
    }
});

$app->delete('/reservations/{id}', function($request, $response, $args) use ($resDao) {
    if ($resDao->deleteReservation($args['id'])) {
        return $response->withStatus(204);
    } else {
        return $response->withStatus(404);
    }
});