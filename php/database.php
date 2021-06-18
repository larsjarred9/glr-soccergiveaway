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

