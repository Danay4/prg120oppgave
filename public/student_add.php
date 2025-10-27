<?php
require_once __DIR__ . '/../db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Feedback flags
$ok  = isset($_GET['ok']);
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $brukernavn = trim($_POST['brukernavn'] ?? '');
  $fornavn    = trim($_POST['fornavn'] ?? '');
  $etternavn  = trim($_POST['etternavn'] ?? '');
  $klassekode = trim($_POST['klassekode'] ?? '');

  if ($brukernavn === '' || $fornavn === '' || $etternavn === '' || $klassekode === '') {
    $err = 'Fyll ut alle feltene.';
  } elseif (strlen($brukernavn) > 7) {
    $err = 'Brukernavn kan være maks 7 tegn.';
  } else {
    $stmt = mysqli_prepare($db, 'INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?,?,?,?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $brukernavn, $fornavn, $etternavn, $klassekode);
    try {
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      if (!headers_sent()) {
        header('Location: student_add.php?ok=1');
        exit;
      } else {
        $ok = true; // fallback: show success without redirect
      }
    } catch (Throwable $e) {
      $code = method_exists($e, 'getCode') ? $e->getCode() : 0;
      if ($code == 1062) {
        $err = 'Brukernavn finnes fra før. Velg et annet.';
      } elseif ($code == 1452) { // FK fail (ukjent klassekode)
        $err = 'Ugyldig klassekode. Velg en eksisterende klasse.';
      } else {
        $err = 'Kunne ikke lagre på grunn av en teknisk feil.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <title>Registrer student</title>
  <script src="./funksjoner.js?v=1"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      if (typeof visSuksessFraQuery === 'function') {
        visSuksessFraQuery('Student registrert!', 'ok');
      }
    });
  </script>
</head>
<body>
  <h1>Registrer ny student</h1>
  <?php if ($ok): ?>
    <p style="background:#eef;border:1px solid #99f;padding:.5rem">Student registrert!</p>
    <script>(typeof visBekreftelse==='function'?visBekreftelse:alert)('Student registrert!');</script>
  <?php endif; ?>
  <?php if ($err): ?>
    <p style="background:#fee;border:1px solid #f99;padding:.5rem"><?php echo htmlspecialchars($err); ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Brukernavn:</label>
    <input type="text" name="brukernavn" maxlength="7" required><br>

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
