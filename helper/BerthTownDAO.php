<?php

namespace dao;

require_once 'helper/DbConnection.php';

use function database\getDBConnection as openDatabase;
use PDO;

class BerthTownDAO
{
    /**
     * Gets all towns with berths
     * @return array: all towns with berths
     */
    public function getAllBerthTowns() {
        $db = openDatabase();
        $selectBerth = $db->prepare('SELECT * FROM berthTowns');

        if ($selectBerth->execute()) {
            return array_map(function($berthTown) {
                $berthT = array();
                $berthT['BerthTownId'] = intval($berthTown['BerthTownId']);
                $berthT['TownName'] = $berthTown['TownName'];
                return $berthT;
            }, $selectBerth->fetchAll(PDO::FETCH_ASSOC));
        } else {
            throw new Exception("There appears to be a problem with the database connection");
        }
    }
}