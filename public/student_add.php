<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $brukernavn = $_POST['brukernavn'];
  $fornavn = $_POST['fornavn'];
  $etternavn = $_POST['etternavn'];
  $klassekode = $_POST['klassekode'];

  $query = "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode)
            VALUES ('$brukernavn', '$fornavn', '$etternavn', '$klassekode')";
  mysqli_query($db, $query);
  echo "<p>Student registrert!</p>";
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <title>Registrer student</title>
</head>
<body>
  <h1>Registrer ny student</h1>

  <form method="post">
    <label>Brukernavn:</label>
    <input type="text" name="brukernavn" required><br>

    <label>Fornavn:</label>
    <input type="text" name="fornavn" required><br>

    <label>Etternavn:</label>
    <input type="text" name="etternavn" required><br>

    <label>Klassekode:</label>
    <select name="klassekode" required>
      <?php
      $result = mysqli_query($db, "SELECT klassekode FROM klasse");
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['klassekode']}'>{$row['klassekode']}</option>";
      }
      ?>
    </select><br><br>

    <input type="submit" value="Lagre student">
  </form>

  <p><a href="../index.php">Tilbake til hovedsiden</a></p>
</body>
</html>
