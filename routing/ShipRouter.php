<?php

$app->get('/ships', function($request, $response) use ($shipDao) {
    return $response->withJson($shipDao->getAllShips(), 200);
})->add($authenticate);

$app->post('/ships', function($request, $response) use ($shipDao) {
    $jsonReq = $request->getParsedBody();

    $shipId = $shipDao->createShip($jsonReq);
    if ($shipId == false) {
        return $response->write('Bad Request.')->withStatus(400);
    } else {
        $jsonReq['shipId'] = $shipId;
        return $response->withJson($jsonReq, 201);
    }
})->add($authenticate);

$app->delete('/ships/{id}', function($request, $response, $args) use ($shipDao) {
    $shipId = intval($args['id']);
    if (!$shipDao->exists($shipId)) {
        return $response->write('Ship not found')->withStatus(404);
    }

    if ($shipDao->deleteShip($shipId)) {
        return $response->withStatus(204);
    } else {
        return $response->write('Server error')->withStatus(500);
    }
})->add($authenticate);