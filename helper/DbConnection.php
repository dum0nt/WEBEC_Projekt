<?php

namespace database;

use PDO;

/**
 * Returns a database connection
 * @return PDO database connection object
 */
function getDBConnection() {
    try {
        return new PDO('mysql:host=localhost:3306;dbname=ahoy;charset=utf8', 'root', '');
    }
    catch(PDOException $e) {
        exit('Keine Verbindung: Grund - ' . $e->getMessage());
    }
}