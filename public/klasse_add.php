<?php
require_once __DIR__ . '/../db.php';
$err = ""; $ok = isset($_GET['ok']);

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $kode = trim($_POST['klassekode'] ?? '');
  $navn = trim($_POST['klassenavn'] ?? '');
  $stud = trim($_POST['studiumkode'] ?? '');
  if ($kode==='' || $navn==='' || $stud==='') {
    $err = "Fyll ut alle feltene.";
  } elseif (strlen($kode) > 5) {
    $err = "Klassekode kan være maks 5 tegn.";
  } else {
    $stmt = mysqli_prepare($db, "INSERT INTO klasse(klassekode, klassenavn, studiumkode) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, "sss", $kode, $navn, $stud);
    if (mysqli_stmt_execute($stmt)) {
      header("Location: klasse_add.php?ok=1"); exit;
    } else {
      $err = "Kunne ikke lagre (er klassekoden unik?).";
    }
  }
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8"><title>Klasser – legg til</title>
  <style>label{display:block;margin:.4rem 0}input,button{padding:.4rem}</style>
</head>
<body>
  <a href="klasse_list.php">← Til liste</a>
  <h1>Legg til klasse</h1>
  <?php if($ok): ?><p style="background:#eef;border:1px solid #99f;padding:.5rem">Lagret!</p><?php endif; ?>
  <?php if($err): ?><p style="background:#fee;border:1px solid #f99;padding:.5rem"><?= htmlspecialchars($err) ?></p><?php endif; ?>

  <form method="post">
    <label>Klassekode (maks 5)
      <input name="klassekode" maxlength="5" required>
    </label>
    <label>Klassenavn
      <input name="klassenavn" required>
    </label>
    <label>Studiumkode
      <input name="studiumkode" required>
    </label>
    <button type="submit">Lagre</button>
  </form>
</body>
</html>
