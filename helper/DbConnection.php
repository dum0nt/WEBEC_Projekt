<?php

namespace database;

use PDO;

function getDBConnection($connectionString, $user, $pwd) {

    try {

        return new PDO($connectionString, $user, $pwd);
    }
    catch(PDOException $e) {
        exit('Keine Verbindung: Grund - ' . $e->getMessage());
    }
}