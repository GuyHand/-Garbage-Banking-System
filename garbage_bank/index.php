<?php
require_once 'db.php';
// แก้ไข: เปลี่ยนชื่อคอลัมน์ให้ตรงกับ rewards.php
// ใช้ title, points_required, image
$rewards = $pdo->query("SELECT id, title, points_required, image FROM rewards ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ธนาคารขยะ</title>
<link rel="stylesheet" href="assets/style.css">
<style>
    .menu {
        text-align: center; /* สั่งให้เนื้อหา (ปุ่ม a) ข้างในอยู่ตรงกลาง */
    }
    
  /* ...CSS เดิมทั้งหมดของคุณคงไว้... */
  .container:has(.reward-list) {
    text-align: center;
    border: 2px solid #C8E6C9;
    background-color: #FFFFFF;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-top: 20px;
    margin-bottom: 20px;
  }
  .menu a {
    text-align: center;
    display: inline-block;
    border: 2px solid #4CAF50;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    color: #2E7D32;
    margin: 4px;
    background-color: #FFFFFF;
    transition: all 0.3s ease;
  }
  .menu a:hover {
    background-color: #E8F5E9;
    color: #45a049;
    border-color: #45a049;
  }
  @keyframes rainbow-border-animation {
    0%   { border-color: #FF0000; }
    14%  { border-color: #FFA500; }
    28%  { border-color: #FFFF00; }
    42%  { border-color: #008000; }
    57%  { border-color: #0000FF; }
    71%  { border-color: #4B0082; }
    85%  { border-color: #EE82EE; }
    100% { border-color: #FF0000; }
  }
  .menu a:active {
    animation: rainbow-border-animation 2s linear infinite;
    border-width: 3px; 
  }
    .reward-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .reward-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        padding: 15px;
        text-align: center;
        transition: 0.3s;
    }
    .reward-card:hover {
        transform: scale(1.05);
    }
    .reward-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 10px;
        /* (แถม) เผื่อรูปโหลดไม่ขึ้น */
        background-color: #f0f0f0; 
        color: #aaa;
    }
    .reward-card h3 {
        color: #2b5b3c;
        margin: 8px 0 4px;
    }
    .reward-card p {
        color: #555;
        font-size: 14px;
    }
    .empty-rewards {
        text-align: center;
        grid-column: 1 / -1; /* ทำให้ข้อความอยู่ตรงกลาง grid */
        color: #777;
        padding: 40px 0;
    }
    .menu-box {
    background-color: #099b35ff; /* สีเขียวฟ้า (Mint Green) */
    padding: 20px;
    border-radius: 12px;     /* ทำให้ขอบมน */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* เพิ่มเงาจางๆ */
}

/* * 2. ปรับ h1 และ .menu ที่อยู่ข้างใน
 * (เพื่อให้ระยะห่างสวยงาม)
 */
.menu-box h1 {
    text-align: center;  /* จัดกลาง (ถ้ายังไม่กลาง) */
    margin-top: 0;       /* ลบระยะห่างบนสุด */
    margin-bottom: 20px; /* เพิ่มระยะห่างระหว่างหัวข้อกับปุ่ม */
    color: #000000ff;    /* ทำให้สีหัวข้อเข้มขึ้น */
}

.menu {
    text-align: center; /* โค้ดเดิมที่จัดกลางปุ่ม */
    padding-bottom: 10px; /* เพิ่มระยะห่างล่างสุดเล็กน้อย */
}
</style>
</head>
<body>
<div class="container">

    <div class="menu-box">

        <h1>เมนูหลัก</h1>

        <div class="menu">
            <a href="add_deposit.php">บันทึกฝากขยะ</a>
            <a href="members.php">สมาชิก</a>
            <a href="rates.php">อัตราแต้ม & รูปภาพขยะ</a>
            <a href="rewards.php">รางวัล</a>
            <a href="transactions.php">รายงานธุรกรรม</a>
        </div>

    </div>
    <hr style="margin:40px 0;border:0;border-top:1px solid #ccc;">
    <h2 style="color:#2b5b3c;">🎁 ของรางวัลทั้งหมด</h2>
    
    </div>
    <div class="reward-list">
        
        <?php if (empty($rewards)): ?>
            <div class="empty-rewards">
                <p>ยังไม่มีของรางวัลในระบบ</p>
            </div>
        <?php else: ?>
            <?php foreach($rewards as $rw): ?>
            <div class="reward-card">
                
                <img src="<?= htmlspecialchars($rw['image']) ?>" 
                     alt="<?= htmlspecialchars($rw['title'] ?? '') ?>"> <h3><?= htmlspecialchars($rw['title'] ?? 'ไม่มีชื่อรางวัล') ?></h3>
                
                <p>ใช้แต้มแลก: <?= number_format($rw['points_required'] ?? 0) ?> แต้ม</p>

            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>
</body>
</html>