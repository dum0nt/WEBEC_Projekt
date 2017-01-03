<?php

$app->get('/ships', function($request, $response) use ($shipDao) {
    return $response->withJson($shipDao->getAllShips());
});

$app->post('/ships', function($request, $response) use ($shipDao) {
    $json = $request->getParsedBody();
    $shipName = $json['shipName'];
    $shipType = $json['shipType'];
    $berthId = $json['berthId'];

    $shipId = $shipDao->createShip($shipName, $shipType, $berthId);
    if ($shipId == false) {
        return $response->withStatus(400);
    } else {
        $json['shipId'] = $shipId;
        return $response->withJson($json, 201);
    }
});

$app->delete('/ships/{id}', function($request, $response, $args) use ($shipDao) {
    if ($shipDao->deleteShip($args['id'])) {
        return $response->withStatus(204);
    } else {
        return $response->withStatus(404);
    }
});