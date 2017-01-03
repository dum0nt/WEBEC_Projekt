<?php

namespace dao;

require_once 'helper/DbConnection.php';

use function database\getDBConnection as openDatabase;
use PDO;

class ShipDAO
{
    /**
     * Returns an array of all ships in the database
     * @return array: All ships in the database
     */
    public function getAllShips() {
        $db = openDatabase();
        $selectShips = $db->prepare('SELECT * FROM ships');

        if ($selectShips->execute()) {
            return $selectShips->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("There appears to be a problem with the database connection");
        }
    }

    /**
     * Creates a new ship database entry
     * @param $ship: a valid ship
     * @return int: id of the ship if create was successful otherwise false
     */
    public function createShip($ship) {
        $db = openDatabase();

        $insertShip = $db->prepare('INSERT INTO ships (ShipName, ShipType, BerthId) VALUES (:shipName, :shipType, :berthId)');
        $insertShip->bindParam(':shipName', $ship['shipName']);
        $insertShip->bindParam(':shipType', $ship['shipType']);
        $insertShip->bindParam(':berthId', $ship['berthId']);

        if ($insertShip->execute()) {
            return intval($db->lastInsertId());
        } else {
            throw new Exception("There appears to be a problem with the database connection");
        }
    }

    /**
     * Returns the ship with the given id
     * @param $shipId: id of the ship to retrieve
     * @return bool|mixed: ship data or false if there was an db error
     */
    public function getShip($shipId) {
        $db = openDatabase();
        $selectBerth = $db->prepare('SELECT * FROM ships WHERE ShipId = :shipId');
        $selectBerth->bindParam(':shipId', $shipId);

        if (!$selectBerth->execute()) {
            return false;
        }

        return $selectBerth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Deletes a ship from the database
     * @param $shipId: id of the ship to be deleted
     * @return bool: true if the ship was deleted, false if there was an db error
     */
    public function deleteShip($shipId) {
        $db = openDatabase();
        $deleteShip = $db->prepare('DELETE FROM ships WHERE ShipId = :shipId');
        $deleteShip->bindParam(':shipId', $shipId);

        return $deleteShip->execute();
    }

    /**
     * Indicates if a ship with a given id exists
     * @param $shipId: ship to be searched
     * @return bool: true if the ship exists
     */
    public function exists($shipId) {
        $db = openDatabase();
        $count = $db->prepare('SELECT * FROM ships WHERE ShipId = :shipId');
        $count->bindParam(':shipId', $shipId);

        if (!$count->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        return count($count->rowCount()) > 0;
    }
}