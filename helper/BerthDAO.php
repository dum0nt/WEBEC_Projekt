<?php

require_once 'helper/DbConnection.php';
use function database\getDBConnection as openDatabase;

class BerthDAO
{
    /**
     * Gets all berths
     * @return array: all berths
     */
    public function getBerths() {
        $db = openDatabase();
        $selectBerth = $db->prepare('SELECT * FROM berths');

        if ($selectBerth->execute()) {
            return array_map(function($berth) {
                $ber = array();
                $ber['BerthId'] = intval($berth['BerthId']);
                $ber['BerthNumber'] = intval($berth['BerthNumber']);
                $ber['IsOccupied'] = boolval($berth['IsOccupied']);
                $ber['BerthTownId'] = intval($berth['BerthTownId']);
                return $ber;
            }, $selectBerth->fetchAll(PDO::FETCH_ASSOC));
        } else {
            throw new Exception("There appears to be a problem with the database connection");
        }
    }

    /**
     * Sets a berth as occupied or vacant
     * @param $berthId: Berth to be updated
     * @param $isOccupied: True if berth will be occupied
     * @return bool: True if occupation could be changed
     */
    public function setBerthOccupied($berthId, $isOccupied) {
        $db = openDatabase();
        $updateBerth = $db->prepare('UPDATE berths SET IsOccupied = :isOccupied WHERE BerthId = :berthId');
        $updateBerth->bindParam(':isOccupied', $isOccupied);
        $updateBerth->bindParam(':berthId', $berthId);

        return $updateBerth->execute();
    }
}