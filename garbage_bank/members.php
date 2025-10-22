<?php require 'db.php'; ?>
<?php
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_id'])){
    $id = intval($_POST['delete_id']);
    $pdo->prepare('DELETE FROM members WHERE id = ?')->execute([$id]);
    header('Location: members.php'); exit;
}
$members = $pdo->query('SELECT * FROM members ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="th">
<head><meta charset="utf-8"><title>สมาชิก</title><link rel="stylesheet" href="assets/style.css"></head>
<body><div class="container">
  <div class="header"><div class="logo">ธข</div><div><h2>สมาชิก</h2></div><div style="margin-left:auto"><a href="index.php" class="btn secondary">กลับ</a></div></div>
  <div class="card">
    <a href="edit_member.php" class="btn">เพิ่มสมาชิกใหม่</a>
    <table class="table">
      <thead><tr><th>ชื่อ</th><th>อีเมล</th><th>เบอร์</th><th>แต้ม</th><th>จัดการ</th></tr></thead>
      <tbody>
      <?php foreach($members as $m): ?>
        <tr>
          <td><?php echo htmlspecialchars($m['name']); ?></td>
          <td><?php echo htmlspecialchars($m['email']); ?></td>
          <td><?php echo htmlspecialchars($m['phone']); ?></td>
          <td><?php echo htmlspecialchars($m['points']); ?></td>
          <td>
            <a href="edit_member.php?id=<?php echo $m['id']; ?>">แก้ไข</a> |
            <form style="display:inline" method="post" onsubmit="return confirm('ลบสมาชิก?');"><input type="hidden" name="delete_id" value="<?php echo $m['id']; ?>"><button class="btn secondary">ลบ</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div></body></html>
