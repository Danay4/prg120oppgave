<?php
require_once __DIR__ . '/../db.php';

$err = "";
$deleted = isset($_GET['deleted']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kode = $_POST['klassekode'] ?? '';
  if ($kode !== '') {
    // Sjekk om det finnes studenter som peker på denne klassen (for FK)
    $chk = mysqli_prepare($db, "SELECT COUNT(*) FROM student WHERE klassekode = ?");
    mysqli_stmt_bind_param($chk, "s", $kode);
    mysqli_stmt_execute($chk);
    mysqli_stmt_bind_result($chk, $antall);
    mysqli_stmt_fetch($chk);
    mysqli_stmt_close($chk);

    if ((int)$antall > 0) {
      $err = "Kan ikke slette: finnes studenter som peker på denne klassen.";
    } else {
      $stmt = mysqli_prepare($db, "DELETE FROM klasse WHERE klassekode = ?");
      mysqli_stmt_bind_param($stmt, "s", $kode);
      try {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: klasse_delete.php?deleted=1');
        exit;
      } catch (Throwable $e) {
        // Dersom mysqli er konfigurert til å kaste exceptions
        $err = "Kunne ikke slette klassen.";
      }
    }
  }
}

$klasser = mysqli_query($db, "SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode");
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett klasse</title>
  <style>
    table { border-collapse: collapse }
    td, th { border: 1px solid #ccc; padding: .4rem }
    form { display: inline }
  </style>
  <script src="funksjoner.js"></script>
  </head>
<body>
  <a href="klasse_list.php">Til liste</a>
  <h1>Slett klasse</h1>
  <?php if ($deleted): ?>
    <p style="background:#eef;border:1px solid #99f;padding:.5rem">Slettet!</p>
  <?php endif; ?>
  <?php if ($err): ?>
    <p style="background:#fee;border:1px solid #f99;padding:.5rem"><?=
      htmlspecialchars($err)
    ?></p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Klassekode</th>
        <th>Klassenavn</th>
        <th>Studiumkode</th>
        <th>Handling</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = mysqli_fetch_assoc($klasser)): ?>
        <tr>
          <td><?= htmlspecialchars($r['klassekode']) ?></td>
          <td><?= htmlspecialchars($r['klassenavn']) ?></td>
          <td><?= htmlspecialchars($r['studiumkode']) ?></td>
          <td>
            <form method="post" onsubmit="return bekreft();">
              <input type="hidden" name="klassekode" value="<?= htmlspecialchars($r['klassekode']) ?>">
              <button type="submit">Slett</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      <?php if (mysqli_num_rows($klasser) === 0): ?>
        <tr><td colspan="4">Ingen klasser registrert.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>

