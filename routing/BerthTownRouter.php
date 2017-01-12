<?php

$app->get('/berthtowns', function($request, $response) use ($berthTownDao) {
    return $response->withJson($berthTownDao->getAllBerthTowns(), 200);
})->add($authenticate);