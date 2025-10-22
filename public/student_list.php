<?php
require_once __DIR__ . '/../db.php';
?>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <title>Vis alle studenter</title>
</head>
<body>
  <h1>Liste over studenter</h1>
  <table border="1" cellpadding="5">
    <tr>
      <th>Brukernavn</th>
      <th>Fornavn</th>
      <th>Etternavn</th>
      <th>Klassekode</th>
    </tr>

    <?php
    $result = mysqli_query($db, "SELECT * FROM student");

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>{$row['brukernavn']}</td>";
      echo "<td>{$row['fornavn']}</td>";
      echo "<td>{$row['etternavn']}</td>";
      echo "<td>{$row['klassekode']}</td>";
      echo "</tr>";
    }
    ?>
  </table>

  <p><a href="../index.php">Tilbake til hovedsiden</a></p>
</body>
</html>
