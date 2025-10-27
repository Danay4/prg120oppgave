<?php
require_once __DIR__ . '/../db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Feedback flags
$err = '';
$flash = $_SESSION['flash_success'] ?? '';
if ($flash !== '') { unset($_SESSION['flash_success']); }

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
        $_SESSION['flash_success'] = 'Du er registrert!';
        header('Location: student_add.php');
        exit;
      } else {
        $flash = 'Du er registrert!'; // fallback: show success without redirect
      }
    } catch (Throwable $e) {
      $code = method_exists($e, 'getCode') ? $e->getCode() : 0;
      if ($code == 1062) {
        $err = 'Studenten er allerede registrert (brukernavn finnes).';
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
  <style>.msg{margin:.5rem 0;padding:.5rem .75rem;border-radius:.25rem}.ok{background:#eaffea;border:1px solid #b6e3b6}.err{background:#ffecec;border:1px solid #f5b5b5}</style>
</head>
<body>
  <h1>Registrer ny student</h1>
  <?php if ($flash): ?>
    <p class="msg ok"><?php echo htmlspecialchars($flash); ?></p>
  <?php endif; ?>
  <?php if ($err): ?>
    <p class="msg err"><?php echo htmlspecialchars($err); ?></p>
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

  <p><a href="index.php">Tilbake til hovedsiden</a></p>
</body>
</html>




