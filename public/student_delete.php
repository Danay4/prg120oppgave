 <?php
require __DIR__ . '/../db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$ok = isset($_GET['deleted']);
$rows = [];
$res = $db->query('SELECT brukernavn, fornavn, etternavn, klassekode FROM student ORDER BY brukernavn');
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
$res->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bn = trim($_POST['brukernavn'] ?? '');
  if ($bn !== '') {
    $stmt = $db->prepare('DELETE FROM student WHERE brukernavn = ?');
    $stmt->bind_param('s', $bn);
    $stmt->execute();
    $stmt->close();
    header('Location: student_delete.php?deleted=1');
    exit;
  }
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett student</title>
  <script src="funksjoner.js"></script>
  <style>
    table { border-collapse: collapse; }
    th, td { border: 1px solid #bbb; padding: .4rem .6rem; }
    th { background: #f5f5f5; text-align: left; }
    .msg{margin:.5rem 0;padding:.5rem .75rem;border-radius:.25rem}
    .ok{background:#eaffea;border:1px solid #b6e3b6}
  </style>
</head>
<body>
<h1>Slett student</h1>
<?php if ($ok): ?><div class="msg ok">Student slettet.</div><?php endif; ?>

<table>
  <thead>
    <tr>
      <th>Brukernavn</th>
      <th>Navn</th>
      <th>Klasse</th>
      <th>Handling</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['brukernavn']) ?></td>
      <td><?= htmlspecialchars($r['fornavn'] . ' ' . $r['etternavn']) ?></td>
      <td><?= htmlspecialchars($r['klassekode']) ?></td>
      <td>
        <form method="post" onsubmit="return bekreft()" style="display:inline;">
          <input type="hidden" name="brukernavn" value="<?= htmlspecialchars($r['brukernavn']) ?>">
          <button type="submit">Slett</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<p><a href="student_list.php">← Til liste</a></p>
</body>
</html>
