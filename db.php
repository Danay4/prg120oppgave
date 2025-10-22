 <?php
/* db-tilkobling – fungerer lokalt og på Dokploy */
$host     = getenv('DB_HOST')     ?: '127.0.0.1';
$username = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_DATABASE') ?: 'prg120oppgave';

$db = mysqli_connect($host, $username, $password, $database)
      or die("ikke kontakt med database-server");
