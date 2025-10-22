<?php require 'db.php'; ?>
<?php
$upload_dir = 'assets/uploaded_images/';
if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['delete_id'])){
        $id = intval($_POST['delete_id']);
        $pdo->prepare('DELETE FROM rates WHERE id = ?')->execute([$id]);
        header('Location: rates.php'); exit;
    }
    $type_name = trim($_POST['type_name']);
    $ppk = floatval($_POST['point_per_kg']);
    $imgpath = null;
    if(!empty($_FILES['image']['name'])){
        $tmp = $_FILES['image']['tmp_name'];
        $name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($tmp, $upload_dir.$name);
        $imgpath = $upload_dir.$name;
    }
    if(!empty($_POST['id'])){
        if($imgpath){
            $stmt = $pdo->prepare('UPDATE rates SET type_name=?, point_per_kg=?, image=? WHERE id=?');
            $stmt->execute([$type_name,$ppk,$imgpath,$_POST['id']]);
        } else {
            $stmt = $pdo->prepare('UPDATE rates SET type_name=?, point_per_kg=? WHERE id=?');
            $stmt->execute([$type_name,$ppk,$_POST['id']]);
        }
    } else {
        $stmt = $pdo->prepare('INSERT INTO rates (type_name, point_per_kg, image) VALUES (?,?,?)');
        $stmt->execute([$type_name,$ppk,$imgpath]);
    }
    header('Location: rates.php'); exit;
}
$rates = $pdo->query('SELECT * FROM rates ORDER BY type_name')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>อัตราแต้ม & รูปภาพขยะ</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">ธข</div><div><h2>อัตราแต้ม & รูปภาพขยะ</h2></div><div style="margin-left:auto"><a href="index.php" class="btn secondary">กลับ</a></div></div>
  <div class="card">
    <h3>เพิ่ม/แก้ไขประเภทขยะ</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id">
      <div class="form-row"><div class="col"><label>ชื่อประเภท</label><input name="type_name" class="input" required></div><div class="col"><label>แต้มต่อกก</label><input name="point_per_kg" class="input" type="number" step="0.001" value="1" required></div></div>
      <div class="form-row"><div class="col"><label>รูปภาพ (ขนาดแนะนำ 200x200)</label><input name="image" type="file" accept="image/*"></div></div>
      <div style="margin-top:8px;"><button class="btn">บันทึก</button></div>
    </form>

    <h3 style="margin-top:18px;">รายการประเภทขยะ</h3>
    <table class="table"><thead><tr><th>รูป</th><th>ประเภท</th><th>แต้ม/กก</th><th>จัดการ</th></tr></thead><tbody>
    <?php foreach($rates as $r): ?>
      <tr><td><?php if($r['image']): ?><img src="<?php echo htmlspecialchars($r['image']); ?>" class="image-thumb"><?php else: ?><div class="image-thumb" style="display:flex;align-items:center;justify-content:center;background:#f6fff6;color:#6b6b6b;">?</div><?php endif; ?></td>
      <td><?php echo htmlspecialchars($r['type_name']); ?></td><td><?php echo $r['point_per_kg']; ?></td>
      <td>
        <form style="display:inline" method="post"><input type="hidden" name="delete_id" value="<?php echo $r['id']; ?>"><button class="btn secondary">ลบ</button></form>
      </td></tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
</div></body></html>
