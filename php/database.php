<?php
 
// Using Medoo namespace.
use Medoo\Medoo;
 
// Connect the database.
$database = new Medoo([
    'type' => 'mariaDB',
    'host' => 'localhost',
    'database' => 'glrsoccer',
    'username' => 'root',
    'password' => ''
]);

// database Logingegevens
$db_hostname = 'localhost'; //of '127.0.0.1'
$db_username = 'root';
$db_password = '';
$db_database = 'glrsoccer';
// maak de database-verbinding
$conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
?>
