<?php

require_once 'helper/DbConnection.php';
use function database\getDBConnection as openDatabase;

class ShipDAO
{

    /**
     * Retrieves all ships
     * @return array|bool: returns array of ships or false if there was a database failure
     */
    public function getAllShips() {
        $db = openDatabase();
        $selectShips = $db->prepare('SELECT * FROM ships');

        if (!$selectShips->execute()) {
            return false;
        }

        return array_map(function($ship) {
            $res = array();
            $res['ShipId'] = intval($ship['ShipId']);
            $res['ShipName'] = $ship['ShipName'];
            $res['ShipType'] = $ship['ShipType'];
            $res['BerthId'] = intval($ship['BerthId']);
            return $res;
        }, $selectShips->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Returns the name of a ship with a given id
     * @param $shipId : id of the ship
     * @return mixed : name of the ship
     * @throws Exception: if there is a database error
     */
    public function getShipName($shipId) {
        $db = openDatabase();
        $selectName = $db->prepare('SELECT ShipName FROM ships WHERE ShipId = :shipId');
        $selectName->bindParam(':shipId', $shipId);

        if ($selectName->execute()) {
            $ship = $selectName->fetch(PDO::FETCH_ASSOC);
            return $ship['ShipName'];
        } else {
            throw new Exception("There appears to be a problem with the database connection");
        }
    }

    /**
     * Creates a new ship database entry
     * @param $ship: a valid ship
     * @return int|bool: id of the ship if create was successful otherwise false
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
            return false;
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
     * @param $shipId : ship to be searched
     * @return bool : true if the ship exists
     * @throws Exception: if there is a database error
     */
    public function exists($shipId) {
        $db = openDatabase();
        $count = $db->prepare('SELECT * FROM ships WHERE ShipId = :shipId');
        $count->bindParam(':shipId', $shipId);

        if (!$count->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        return $count->rowCount() > 0;
    }
}