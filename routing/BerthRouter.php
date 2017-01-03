<?php

$app->get('/berths', function($request, $response) use ($berthDao) {
    $params = $request->getQueryParams();
    if (isset($params['town'])) {
        $berths = array_filter($berthDao->getBerths(), function($berth) use ($params) {
            return $berth['BerthTownId'] == intval($params['town']);
        });
        return $response->withJson(array_values($berths), 200);
    } else {
        return $response->withJson(json_encode(false), 403);
    }
});