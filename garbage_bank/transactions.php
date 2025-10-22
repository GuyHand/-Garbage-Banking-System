<?php require 'db.php'; ?>
<?php
$transactions = $pdo->query('SELECT t.*, m.name as member_name, r.type_name FROM transactions t JOIN members m ON t.member_id=m.id JOIN rates r ON t.rate_id=r.id ORDER BY t.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>รายงานธุรกรรม</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">ธข</div><div><h2>รายงานธุรกรรม</h2><div class="small">แสดงบันทึกการฝากทั้งหมด</div></div><div style="margin-left:auto"><a href="index.php" class="btn secondary">กลับ</a></div></div>
  <div class="card">
    <table class="table"><thead><tr><th>วันที่</th><th>สมาชิก</th><th>ประเภท</th><th>น้ำหนัก (กก)</th><th>แต้ม</th></tr></thead><tbody>
    <?php foreach($transactions as $t): ?>
      <tr><td><?php echo $t['created_at']; ?></td><td><?php echo htmlspecialchars($t['member_name']); ?></td><td><?php echo htmlspecialchars($t['type_name']); ?></td><td><?php echo $t['weight']; ?></td><td><?php echo $t['points_earned']; ?></td></tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
</div></body></html>
