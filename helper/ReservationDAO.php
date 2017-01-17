<?php

require_once 'helper/DbConnection.php';
use function database\getDBConnection as openDatabase;

class ReservationDAO
{
    /**
     * Retrieves all reservations
     * @return array : all reservations
     * @throws Exception: if there is a database error
     */
    public function getAllReservations() {
        $db = openDatabase();
        $selectReservations = $db->prepare('SELECT * FROM reservations');

        if ($selectReservations->execute()) {
            $reservations = array_map(function($reservation) {
                $res = array();
                $res['ReservationId'] = intval($reservation['ReservationId']);
                $res['StartTime'] = $reservation['StartTime'];
                $res['EndTime'] = $reservation['EndTime'];
                $res['ShipId'] = intval($reservation['ShipId']);
                $res['UserId'] = intval($reservation['UserId']);
                return $res;
            }, $selectReservations->fetchAll(PDO::FETCH_ASSOC));
            return $reservations;
        } else {
            throw new Exception("There appears to be a problem with the database connection");
        }
    }

    /**
     * Creates a new reservations
     * @param $reservation: a valid reservation
     * @return mixed: ID of the new reservation or false if creation failed
     */
    public function createReservation($reservation) {
        $db = openDatabase();
        $insertRes = $db->prepare('INSERT INTO reservations (StartTime, EndTime, UserId, ShipId) VALUES (:startTime, :endTime, :userId, :shipId)');
        $insertRes->bindParam(':startTime', $reservation['startTime']);
        $insertRes->bindParam(':endTime', $reservation['endTime']);
        $insertRes->bindParam(':userId', $reservation['userId']);
        $insertRes->bindParam(':shipId', $reservation['shipId']);

        if ($insertRes->execute()) {
            return intval($db->lastInsertId());
        } else {
            return false;
        }
    }

    /**
     * Deletes a reservation
     * @param $resId: ID of the reservation to be deleted
     * @return bool: true if the reservation could be deleted, false if there was an db error
     */
    public function deleteReservation($resId) {
        $db = openDatabase();
        $deleteReservation = $db->prepare('DELETE FROM reservations WHERE ReservationId = :resId');
        $deleteReservation->bindParam(':resId', $resId);

        return $deleteReservation->execute();
    }

    /**
     * Indicates if a reservation with a given id exists
     * @param resId : id of the reservation to be searched
     * @return bool : true if the reservation exists
     * @throws Exception: if there is a database error
     */
    public function exists($resId) {
        $db = openDatabase();
        $count = $db->prepare('SELECT * FROM reservations WHERE ReservationId = :resId');
        $count->bindParam(':resId', $resId);

        if (!$count->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        return $count->rowCount() > 0;
    }
}