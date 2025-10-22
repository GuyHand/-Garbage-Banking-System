<?php require 'db.php'; ?>
<?php
// Processing form
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = !empty($_POST['member_id']) ? intval($_POST['member_id']) : null;
    $member_name = trim($_POST['member_name'] ?? '');
    $rate_id = intval($_POST['rate_id']);
    $weight = floatval($_POST['weight']);
    // create member if name provided and no member_id
    if(!$member_id && $member_name) {
        $stmt = $pdo->prepare('INSERT INTO members (name) VALUES (?)');
        $stmt->execute([$member_name]);
        $member_id = $pdo->lastInsertId();
    }
    // get rate
    $stmt = $pdo->prepare('SELECT point_per_kg FROM rates WHERE id = ?');
    $stmt->execute([$rate_id]);
    $rate = $stmt->fetch(PDO::FETCH_ASSOC);
    $points = 0;
    if($rate) {
        $points = $rate['point_per_kg'] * $weight;
    }
    // insert transaction
    $stmt = $pdo->prepare('INSERT INTO transactions (member_id, rate_id, weight, points_earned) VALUES (?,?,?,?)');
    $stmt->execute([$member_id, $rate_id, $weight, $points]);
    // update member points
    $stmt = $pdo->prepare('UPDATE members SET points = points + ? WHERE id = ?');
    $stmt->execute([$points, $member_id]);
    $saved = true;
}
$members = $pdo->query('SELECT * FROM members ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
$rates = $pdo->query('SELECT * FROM rates ORDER BY type_name')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>บันทึกฝากขยะ - ธนาคารขยะ</title>
  <link rel="stylesheet" href="assets/style.css">
  <script>
    function calcPoints() {
      const rateSelect = document.getElementById('rate_id');
      const weight = parseFloat(document.getElementById('weight').value) || 0;
      const rate = parseFloat(rateSelect.selectedOptions[0].dataset.ppk) || 0;
      const pts = (rate * weight).toFixed(3);
      document.getElementById('points').innerText = pts;
    }
  </script>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="logo">ธข</div>
    <div><h2>บันทึกฝากขยะ</h2><div class="small">บันทึกการฝากแล้วคำนวนแต้มให้อัตโนมัติ</div></div>
    <div style="margin-left:auto"><a href="index.php" class="btn secondary">กลับ</a></div>
  </div>

  <div class="card">
    <?php if(!empty($saved)): ?>
      <div style="padding:12px; background:#eaf7ec; border-radius:8px; margin-bottom:12px;">บันทึกสำเร็จ — ได้แต้ม <?php echo htmlspecialchars(round($points,3)); ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="form-row">
        <div class="col">
          <label>เลือกสมาชิก (หรือใส่ชื่อใหม่)</label>
          <select name="member_id" class="input">
            <option value="">-- เลือกสมาชิก --</option>
            <?php foreach($members as $m): ?>
              <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['name']); ?> (<?php echo $m['points']; ?> แต้ม)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col">
          <label>ชื่อสมาชิกใหม่ (ถ้ายังไม่มี)</label>
          <input name="member_name" class="input" placeholder="เช่น นายสมชาย">
        </div>
      </div>

      <div class="form-row">
        <div class="col">
          <label>ประเภทขยะ</label>
          <select id="rate_id" name="rate_id" class="input" onchange="calcPoints()" required>
            <?php foreach($rates as $r): ?>
              <option value="<?php echo $r['id']; ?>" data-ppk="<?php echo $r['point_per_kg']; ?>"><?php echo htmlspecialchars($r['type_name']); ?> — <?php echo $r['point_per_kg']; ?> แต้ม/กก</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col">
          <label>น้ำหนัก (กิโลกรัม)</label>
          <input id="weight" name="weight" class="input" type="number" min="0" step="0.001" value="0" oninput="calcPoints()" required>
        </div>
      </div>

      <div style="margin-top:8px;">คำนวนแต้ม: <strong id="points">0.000</strong> แต้ม</div>
      <div style="margin-top:12px;"><button class="btn">บันทึก</button></div>
    </form>
  </div>
</div>
</body>
</html>
