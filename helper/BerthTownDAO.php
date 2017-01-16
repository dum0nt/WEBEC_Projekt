<?php

namespace dao;
use PDO;
require_once 'helper/DbConnection.php';
use function database\getDBConnection as openDatabase;

class BerthTownDAO
{
    /**
     * Gets all towns with berths
     * @return array: all towns with berths
     */
    public function getAllBerthTowns() {
        $db = openDatabase();
        $selectBerth = $db->prepare('SELECT * FROM berthtowns');

        if ($selectBerth->execute()) {
            return array_map(function($berthTown) {
                $berthT = array();
                $berthT['BerthTownId'] = intval($berthTown['BerthTownId']);
                $berthT['TownName'] = $berthTown['TownName'];
                return $berthT;
            }, $selectBerth->fetchAll(PDO::FETCH_ASSOC));
        } else {
            throw new \Exception("There appears to be a problem with the database connection");
        }
    }
}