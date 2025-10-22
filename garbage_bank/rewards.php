<?php require 'db.php'; ?>
<?php
$upload_dir = 'assets/uploaded_images/';
if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['delete_id'])){
        $pdo->prepare('DELETE FROM rewards WHERE id = ?')->execute([intval($_POST['delete_id'])]);
        header('Location: rewards.php'); exit;
    }
    $title = trim($_POST['title']);
    $points = floatval($_POST['points_required']);
    $img = null;
    if(!empty($_FILES['image']['name'])){
        $n = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir.$n);
        $img = $upload_dir.$n;
    }
    $pdo->prepare('INSERT INTO rewards (title, points_required, image) VALUES (?,?,?)')->execute([$title,$points,$img]);
    header('Location: rewards.php'); exit;
}
$rewards = $pdo->query('SELECT * FROM rewards ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>รางวัล</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">ธข</div><div><h2>รางวัล</h2></div><div style="margin-left:auto"><a href="index.php" class="btn secondary">กลับ</a></div></div>
  <div class="card">
    <h3>เพิ่มรางวัล</h3>
    <form method="post" enctype="multipart/form-data">
      <div class="form-row"><div class="col"><label>ชื่อรางวัล</label><input name="title" class="input" required></div><div class="col"><label>แต้มที่ต้องใช้</label><input name="points_required" class="input" type="number" step="0.01" required></div></div>
      <div class="form-row"><div class="col"><label>รูปภาพ</label><input name="image" type="file" accept="image/*"></div></div>
      <div style="margin-top:8px;"><button class="btn">เพิ่ม</button></div>
    </form>

    <h3 style="margin-top:18px;">รายการรางวัล</h3>
    <table class="table"><thead><tr><th>รูป</th><th>ชื่อ</th><th>แต้ม</th><th>จัดการ</th></tr></thead><tbody>
    <?php foreach($rewards as $rw): ?>
      <tr><td><?php if($rw['image']): ?><img src="<?php echo htmlspecialchars($rw['image']); ?>" class="image-thumb"><?php else: ?><div class="image-thumb">?</div><?php endif; ?></td>
      <td><?php echo htmlspecialchars($rw['title']); ?></td><td><?php echo $rw['points_required']; ?></td>
      <td><form style="display:inline" method="post"><input type="hidden" name="delete_id" value="<?php echo $rw['id']; ?>"><button class="btn secondary">ลบ</button></form></td></tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
</div></body></html>
