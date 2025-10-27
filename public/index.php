<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/'); if ($base === '.') { $base = ''; } ?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>PRG120 – Vedlikeholdsapp</title>
</head>
<body>
  <h1>PRG120 – Vedlikeholdsapp</h1>

  <h2>Klasse</h2>
  <ul>
    <li><a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/klasse_list.php">Vis alle</a></li>
    <li><a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/klasse_add.php">Registrer ny</a></li>
    <li><a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/klasse_delete.php">Slett</a></li>
  </ul>

  <h2>Student</h2>
  <ul>
    <li><a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/student_list.php">Vis alle</a></li>
    <li><a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/student_add.php">Registrer ny</a></li>
    <li><a href="<?= htmlspecialchars($base, ENT_QUOTES) ?>/student_delete.php">Slett</a></li>
  </ul>
</body>
</html>
