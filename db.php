<?php
/* db-tilkobling
   Programmet foretar tilkobling til database-server og database
*/

$host = 'localhost';       // ⚠️ no space before or after
$username = 'root';
$password = '';            // XAMPP default has no password
$database = 'prg120opgave';  // must match phpMyAdmin exactly

$db = mysqli_connect($host, $username, $password, $database)
    or die("Ikke kontakt med database-serveren eller databasen.");

/* tilkobling til database-serveren utført */
?>
