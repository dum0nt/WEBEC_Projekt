<?php

namespace dao;
use PDO;
require_once 'helper/DbConnection.php';
use function database\getDBConnection as openDatabase;

class UserDAO
{
    /**
     * Creates a new user if the username not already exists
     * @param $user: user data
     * @return bool|int: id of the created user or false if username already existed
     */
    public function createUser($user) {
        $db = openDatabase();
        $exists = $db->prepare('SELECT * FROM users WHERE UserName = :userName');
        $exists->bindParam(':userName', $user['userName']);

        if (!$exists->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        if ($exists->rowCount() > 0) {
            // userName already exists
            return false;
        }

        $saltValue = uniqid();
        $passwordHash = md5($user['password'] . $saltValue);

        $insertUser = $db->prepare('INSERT INTO users (UserName, FirstName, LastName, Address, ZIP, City, Email, PasswordHash, SaltValue) VALUES (:userName, :firstName, :lastName, :address, :zip, :city, :email, :passwordHash, :saltValue)');
        $insertUser->bindParam(':userName', $user['userName']);
        $insertUser->bindParam(':firstName', $user['firstName']);
        $insertUser->bindParam(':lastName', $user['lastName']);
        $insertUser->bindParam(':address', $user['address']);
        $insertUser->bindParam(':zip', $user['zip']);
        $insertUser->bindParam(':city', $user['city']);
        $insertUser->bindParam(':email', $user['email']);
        $insertUser->bindParam(':passwordHash', $passwordHash);
        $insertUser->bindParam(':saltValue', $saltValue);

        if (!$insertUser->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        return intval($db->lastInsertId());
    }

    /**
     * Returns the user with the provided id
     * @param $userId: id of the user to be searched
     * @return bool|mixed: returns the user data or false in case of failure
     */
    public function getUser($userId) {
        $db = openDatabase();
        $selectUser = $db->prepare('SELECT * FROM users WHERE UserId = :userId');
        $selectUser->bindParam(':userId', $userId);

        if (!$selectUser->execute()) {
            return false;
        }

        $user = $selectUser->fetch(PDO::FETCH_ASSOC);
        $user['UserId'] = intval($user['UserId']);
        $user['ZIP'] = intval($user['ZIP']);

        return $user;
    }

    /**
     * Updates the password of a user.
     * @param $user: user data
     * @return bool: true if successful, false if old password did not match
     */
    public function updateUserPassword($user) {
        $userId = $user['userId'];
        $db = openDatabase();
        $selectSalt = $db->prepare('SELECT PasswordHash, SaltValue FROM users WHERE UserId = :userId');
        $selectSalt->bindParam(':userId', $userId);

        if (!$selectSalt->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        $response = $selectSalt->fetch(PDO::FETCH_ASSOC);
        $saltValue = $response['SaltValue'];
        $oldPasswordHash = $response['PasswordHash'];

        if (md5($user['oldPassword'] . $saltValue) != $oldPasswordHash) {
            return false;
        }

        $updatePw = $db->prepare('UPDATE users SET PasswordHash = :passwordHash WHERE UserId = :userId');
        $updatePw->bindParam(':passwordHash', md5($user['newPassword'] . $saltValue));
        $updatePw->bindParam(':userId', $userId);

        if (!$updatePw->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        return true;
    }

    /**
     * Deletes a user from the database
     * @param $userId: id of the user to be deleted
     * @return bool: true if the user could be deleted, false if there was an db error
     */
    public function deleteUser($userId) {
        $db = openDatabase();
        $deleteUser = $db->prepare('DELETE FROM users WHERE UserId = :userId');
        $deleteUser->bindParam(':userId', $userId);

        return $deleteUser->execute();
    }

    /**
     * Indicates if a user with a given id exists
     * @param $userId: user to be searched
     * @return bool: true if the user exists
     */
    public function exists($userId) {
        $db = openDatabase();
        $count = $db->prepare('SELECT * FROM users WHERE UserId = :userId');
        $count->bindParam(':userId', $userId);

        if (!$count->execute()) {
            throw new Exception("There appears to be a problem with the database connection");
        }

        return $count->rowCount() > 0;
    }
}