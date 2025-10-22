<?php
require_once 'db.php';

// 1. ดึงข้อมูลทั้ง 2 ส่วน
// ดึงข้อมูลอัตราแต้มจากตาราง rates
$rates = $pdo->query("SELECT * FROM rates ORDER BY type_name")->fetchAll(PDO::FETCH_ASSOC);
// (เพิ่ม) ดึงข้อมูลของรางวัลจากตาราง rewards
$rewards = $pdo->query("SELECT id, title, points_required, image FROM rewards ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ธนาคารขยะ</title>
<link rel="stylesheet" href="assets/style.css">
<style>
/* ... (CSS .menu, .container, .menu-box, .menu a ทั้งหมดคงไว้เหมือนเดิม) ... */
.menu {
    text-align: center;
}
.container:has(.table) { /* (ปรับปรุงเล็กน้อย) */
    border: 2px solid #C8E6C9;
    background-color: #FFFFFF;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-top: 20px;
    margin-bottom: 20px;
}
.menu a {
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
.menu-box {
    background-color: #099b35ff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.menu-box h1 {
    text-align: center;
    margin-top: 0;
    margin-bottom: 20px;
    color: #000000ff;
}
.menu {
    text-align: center;
    padding-bottom: 10px;
}
.image-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.table th, .table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
.table th {
    background-color: #e8f5e9;
}

/* 3. (เพิ่ม CSS) สไตล์สำหรับปุ่ม Tab และการ์ดของรางวัล */
.tab-nav {
    text-align: center;
    margin: 20px 0 30px 0;
}
.tab-btn {
    background: #f0f0f0;
    border: 1px solid #ddd;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    margin: 0 5px;
    transition: all 0.3s ease;
    font-weight: 600;
}
.tab-btn.active {
    background-color: #2E7D32;
    color: white;
    border-color: #2E7D32;
}

/* ซ่อนเนื้อหา Tab ที่ไม่ active */
.content-tab {
    display: none; 
}
.content-tab.active {
    display: block;
}

/* (นำ CSS ของรางวัลกลับมา) */
.reward-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.reward-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
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
    background-color: #f0f0f0; 
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
</style>
</head>
<body>
<div class="container">

    <div class="menu-box">
        <h1>เมนูหลัก</h1>
        <div class="menu">
            <a href="add_deposit.php">บันทึกฝากขยะ</a>
            <a href="members.php">สมาชิก</a>
            <a href="rates.php">อัตราแต้ม </a>
            <a href="rewards.php">รางวัล</a>
            <a href="transactions.php">รายงานธุรกรรม</a>
        </div>
    </div>

    <hr style="margin:40px 0;border:0;border-top:1px solid #ccc;">

    <div class="tab-nav">
        <button class="tab-btn active" onclick="showView('rates-view')">♻️ แต้มขยะปัจจุบัน</button>
        <button class="tab-btn" onclick="showView('rewards-view')">🎁 ของรางวัล</button>
    </div>

    <div id="rates-view" class="content-tab active">
        <h2 style="color:#2b5b3c; text-align:center;">♻️ แต้มขยะปัจจุบัน</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>รูป</th>
                    <th>ประเภท</th>
                    <th>แต้ม/กก.</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rates)): ?>
                <tr>
                    <td colspan="3" style="text-align:center; color:#777; padding:20px;">
                        ยังไม่มีข้อมูลอัตราแต้มในระบบ
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach($rates as $r): ?>
                <tr>
                    <td>
                        <?php if(!empty($r['image'])): ?>
                        <img src="<?php echo htmlspecialchars($r['image']); ?>" class="image-thumb">
                        <?php else: ?>
                        <div class="image-thumb" style="display:flex;align-items:center;justify-content:center;background:#f6fff6;color:#6b6b6b;">?</div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($r['type_name']); ?></td>
                    <td><?php echo $r['point_per_kg']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="rewards-view" class="content-tab">
        <h2 style="color:#2b5b3c; text-align:center;">🎁 ของรางวัลทั้งหมด</h2>
        <div class="reward-list">
            <?php if (empty($rewards)): ?>
            <div class="empty-rewards">
                <p>ยังไม่มีของรางวัลในระบบ</p>
            </div>
            <?php else: ?>
            <?php foreach($rewards as $rw): ?>
            <div class="reward-card">
                <img src="<?= htmlspecialchars($rw['image']) ?>" alt="<?= htmlspecialchars($rw['title'] ?? '') ?>">
                <h3><?= htmlspecialchars($rw['title'] ?? 'ไม่มีชื่อรางวัล') ?></h3>
                <p>ใช้แต้มแลก: <?= number_format($rw['points_required'] ?? 0) ?> แต้ม</p>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
function showView(viewId) {
    // 1. ซ่อนเนื้อหา .content-tab ทั้งหมด
    var tabs = document.querySelectorAll('.content-tab');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });

    // 2. ลบ .active ออกจากปุ่ม .tab-btn ทั้งหมด
    var buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(function(btn) {
        btn.classList.remove('active');
    });

    // 3. แสดง tab ที่เลือก
    document.getElementById(viewId).classList.add('active');

    // 4. ตั้งค่า .active ให้ปุ่มที่ถูกคลิก
    // (เราใช้ viewId ที่ส่งเข้ามาเพื่อหาปุ่มที่ถูกต้อง)
    document.querySelector('.tab-btn[onclick="showView(\'' + viewId + '\')"]').classList.add('active');
}
</script>

</body>
</html> 