 <?php
// public/klasse_delete.php
require_once __DIR__ . '/../db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$err = '';
$ok  = isset($_GET['deleted']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['klassekode'] ?? '');
    if ($kode !== '') {
        try {
            // Delete students first, then the class (no FK crash)
            $db->begin_transaction();

            $s1 = $db->prepare('DELETE FROM student WHERE klassekode = ?');
            $s1->bind_param('s', $kode);
            $s1->execute();
            $s1->close();

            $s2 = $db->prepare('DELETE FROM klasse WHERE klassekode = ?');
            $s2->bind_param('s', $kode);
            $s2->execute();
            $s2->close();

            $db->commit();

            // Reload page so the table updates (row disappears)
            header('Location: klasse_delete.php?deleted=1');
            exit;
        } catch (Throwable $e) {
            if ($db->errno) { $db->rollback(); }
            $err = 'Kunne ikke slette (teknisk feil).';
        }
    } else {
        $err = 'Ugyldig klassekode.';
    }
}

// Fetch list to display
$rows = [];
$res = $db->query('SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode');
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
$res->close();
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett klasse</title>
  <!-- you are inside /public, so no "public/" prefix -->
  <script src="funksjoner.js"></script>
  <style>
    table { border-collapse: collapse; }
    th, td { border: 1px solid #bbb; padding: .4rem .6rem; }
    th { background: #f5f5f5; text-align: left; }
    .msg{margin:.5rem 0;padding:.5rem .75rem;border-radius:.25rem}
    .ok{background:#eaffea;border:1px solid #b6e3b6}
    .err{background:#ffecec;border:1px solid #f5b5b5}
    a{text-decoration:none}
  </style>
</head>
<body>

<p><a href="klasse_list.php">← Til liste</a></p>
<h1>Slett klasse</h1>

<?php if ($ok): ?><div class="msg ok">Klasse slettet.</div><?php endif; ?>
<?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

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
  <?php foreach ($rows as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['klassekode']) ?></td>
      <td><?= htmlspecialchars($row['klassenavn']) ?></td>
      <td><?= htmlspecialchars($row['studiumkode']) ?></td>
      <td>
        <!-- POST per row + confirm -->
        <form method="post" onsubmit="return bekreft()" style="display:inline;">
          <input type="hidden" name="klassekode" value="<?= htmlspecialchars($row['klassekode']) ?>">
          <button type="submit">Slett</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>
