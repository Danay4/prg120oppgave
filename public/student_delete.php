<?php
require_once __DIR__ . '/../db.php';

$deleted = isset($_GET['deleted']);
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $brukernavn = $_POST['brukernavn'] ?? '';
  if ($brukernavn !== '') {
    $stmt = mysqli_prepare($db, 'DELETE FROM student WHERE brukernavn = ?');
    mysqli_stmt_bind_param($stmt, 's', $brukernavn);
    if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_close($stmt);
      header('Location: student_delete.php?deleted=1');
      exit;
    } else {
      $err = 'Kunne ikke slette studenten.';
    }
  }
}

$result = mysqli_query($db, 'SELECT brukernavn, fornavn, etternavn FROM student ORDER BY brukernavn');
?>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <title>Slett student</title>
  <style>
    table { border-collapse: collapse }
    td, th { border: 1px solid #ccc; padding: .4rem }
    form { display: inline }
  </style>
  <script src="funksjoner.js"></script>
</head>
<body>
  <a href="student_list.php">Til liste</a>
  <h1>Slett student</h1>
  <?php if ($deleted): ?>
    <p style="background:#eef;border:1px solid #99f;padding:.5rem">Student slettet!</p>
  <?php endif; ?>
  <?php if ($err): ?>
    <p style="background:#fee;border:1px solid #f99;padding:.5rem"><?=
      htmlspecialchars($err)
    ?></p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Brukernavn</th>
        <th>Navn</th>
        <th>Handling</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['brukernavn']) ?></td>
          <td><?= htmlspecialchars($row['fornavn'] . ' ' . $row['etternavn']) ?></td>
          <td>
            <form method="post" onsubmit="return bekreft();">
              <input type="hidden" name="brukernavn" value="<?= htmlspecialchars($row['brukernavn']) ?>">
              <button type="submit">Slett</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      <?php if (mysqli_num_rows($result) === 0): ?>
        <tr><td colspan="3">Ingen studenter registrert.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <p><a href="../index.php">Tilbake til hovedsiden</a></p>
</body>
</html>
