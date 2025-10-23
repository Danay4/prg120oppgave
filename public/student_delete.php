<?php
require_once __DIR__ . '/../db.php';

if (isset($_GET['brukernavn'])) {
  $brukernavn = $_GET['brukernavn'];
  mysqli_query($db, "DELETE FROM student WHERE brukernavn='$brukernavn'");
  echo "<p>Student slettet!</p>";
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <title>Slett student</title>
  <script src="funksjoner.js"></script>
</head>
<body>
  <h1>Slett student</h1>
  <table border="1" cellpadding="5">
    <tr>
      <th>Brukernavn</th>
      <th>Navn</th>
      <th>Handling</th>
    </tr>
    <?php
    $result = mysqli_query($db, "SELECT * FROM student");

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>{$row['brukernavn']}</td>";
      echo "<td>{$row['fornavn']} {$row['etternavn']}</td>";
      echo "<td><a href='?brukernavn={$row['brukernavn']}' onclick='return bekreft()'>Slett</a></td>";
      echo "</tr>";
    }
    ?>
  </table>

  <p><a href="../index.php">Tilbake til hovedsiden</a></p>
</body>
</html>
