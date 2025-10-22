<?php require 'db.php'; ?>
<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $points = floatval($_POST['points']);
    if(!empty($_POST['id'])){
        $stmt = $pdo->prepare('UPDATE members SET name=?, email=?, phone=?, points=? WHERE id=?');
        $stmt->execute([$name,$email,$phone,$points,$_POST['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO members (name,email,phone,points) VALUES (?,?,?,?)');
        $stmt->execute([$name,$email,$phone,$points]);
    }
    header('Location: members.php'); exit;
}
$member = ['name'=>'','email'=>'','phone'=>'','points'=>0];
if($id){
    $stmt = $pdo->prepare('SELECT * FROM members WHERE id=?');
    $stmt->execute([$id]); $member = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<html lang="th"><head><meta charset="utf-8"><title>แก้ไขสมาชิก</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">ธข</div><div><h2>แก้ไข/เพิ่ม สมาชิก</h2></div><div style="margin-left:auto"><a href="members.php" class="btn secondary">กลับ</a></div></div>
  <div class="card">
    <form method="post">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($member['id'] ?? ''); ?>">
      <div class="form-row"><div class="col"><label>ชื่อ</label><input name="name" class="input" value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>"></div></div>
      <div class="form-row"><div class="col"><label>อีเมล</label><input name="email" class="input" value="<?php echo htmlspecialchars($member['email'] ?? ''); ?>"></div><div class="col"><label>โทร</label><input name="phone" class="input" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>"></div></div>
      <div class="form-row"><div class="col"><label>แต้ม</label><input name="points" class="input" type="number" step="0.01" value="<?php echo htmlspecialchars($member['points'] ?? 0); ?>"></div></div>
      <div style="margin-top:12px;"><button class="btn">บันทึก</button></div>
    </form>
  </div>
</div></body></html>
