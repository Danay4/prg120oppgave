<?php
if (!headers_sent()) {
  header('Location: public/index.php', true, 302);
  exit;
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8"><title>PRG120 – Vedlikeholdsapp</title>
</head>
<body>
  <p>Videresender til meny … Hvis ikke, klikk her: <a href="public/index.php">Meny</a></p>
</body>
</html>
