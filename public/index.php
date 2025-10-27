<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
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
    <li><a href="<?= $base ?>/klasse_list.php">Vis alle</a></li>
    <li><a href="<?= $base ?>/klasse_add.php">Registrer ny</a></li>
    <li><a href="<?= $base ?>/klasse_delete.php">Slett</a></li>
  </ul>

  <h2>Student</h2>
  <ul>
    <li><a href="<?= $base ?>/student_list.php">Vis alle</a></li>
    <li><a href="<?= $base ?>/student_add.php">Registrer ny</a></li>
    <li><a href="<?= $base ?>/student_delete.php">Slett</a></li>
  </ul>
</body>
</html>

