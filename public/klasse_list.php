<?php
require_once __DIR__ . '/../db.php';
$result = mysqli_query($db, "SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode");
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8"><title>Klasser – vis alle</title>
  <style>table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:.4rem}</style>
</head>
<body>
  <a href="../index.php">← Til meny</a> | 
  <a href="klasse_add.php">Legg til</a> | 
  <a href="klasse_delete.php">Slett</a>
  <h1>Alle klasser</h1>
  <table>
    <thead><tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr></thead>
    <tbody>
      <?php while($r = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($r['klassekode']) ?></td>
          <td><?= htmlspecialchars($r['klassenavn']) ?></td>
          <td><?= htmlspecialchars($r['studiumkode']) ?></td>
        </tr>
      <?php endwhile; ?>
      <?php if (mysqli_num_rows($result) === 0): ?>
        <tr><td colspan="3">Ingen klasser registrert.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
