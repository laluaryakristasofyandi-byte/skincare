<?php
session_start();
require 'db.php';

// Cek login + role admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

// Search pelanggan
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = "WHERE role = 'user'";
if($search != ''){
    $where .= " AND (nama LIKE '%$search%' OR email LIKE '%$search%')";
}

$pelanggan = $conn->query("SELECT id,nama,email,created_at FROM users $where ORDER BY id DESC");
$total = $pelanggan->num_rows;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pelanggan - Admin Skincare Shop</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:#FFF5F7;color:#333}
.sidebar{position:fixed;left:0;top:0;width:250px;height:100vh;background:#1e2a3a;color:#fff;padding:30px 0}
.sidebar h2{text-align:center;color:#E91E63;margin-bottom:40px;font-size:22px}
.sidebar a{display:block;padding:15px 30px;color:#ddd;text-decoration:none;transition:0.3s}
.sidebar a:hover,.sidebar a.active{background:#E91E63;color:#fff}
.main{margin-left:250px;padding:40px}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px}
.header h1{font-size:28px;color:#333}
.card{background:#fff;border-radius:15px;padding:30px;box-shadow:0 5px 20px rgba(0,0,0,0.05)}
.search-box{display:flex;gap:10px;margin-bottom:25px}
.search-box input{flex:1;padding:12px 15px;border:1.5px solid #F8BBD0;border-radius:8px;outline:none}
.search-box input:focus{border-color:#E91E63}
.btn{padding:12px 20px;background:#E91E63;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600}
.btn:hover{background:#c2185b}
table{width:100%;border-collapse:collapse}
th{background:#FCE4EC;color:#E91E63;padding:15px;text-align:left;font-weight:600}
td{padding:15px;border-bottom:1px solid #f0f0f0}
tr:hover{background:#FFF5F7}
.badge{background:#E91E63;color:#fff;padding:5px 12px;border-radius:20px;font-size:12px}
.empty{text-align:center;padding:50px;color:#999}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:30px}
.stat-card{background:#fff;padding:25px;border-radius:15px;border-left:4px solid #E91E63}
.stat-card h3{color:#999;font-size:14px;font-weight:400;margin-bottom:8px}
.stat-card p{font-size:28px;font-weight:700;color:#333}
</style>
</head>
<body>

<div class="sidebar">
    <h2>SKINCARE SHOP</h2>
    <a href="admin.php">📊 Dashboard</a>
    <a href="admin_produk.php">📦 Produk</a>
    <a href="admin_kategori.php">🏷️ Kategori</a>
    <a href="pelanggan.php" class="active">👥 Pelanggan</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="header">
        <h1>Data Pelanggan</h1>
        <div>Halo, <?=$_SESSION['user']['nama']?> | Admin</div>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>Total Pelanggan</h3>
            <p><?=$total?></p>
        </div>
        <div class="stat-card">
            <h3>Pelanggan Baru</h3>
            <p><?=$conn->query("SELECT id FROM users WHERE role='user' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->num_rows?></p>
        </div>
        <div class="stat-card">
            <h3>Status</h3>
            <p>Aktif</p>
        </div>
    </div>

    <div class="card">
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari nama atau email pelanggan..." value="<?=$search?>">
            <button type="submit" class="btn">Cari</button>
            <a href="pelanggan.php" class="btn" style="background:#999;text-decoration:none">Reset</a>
        </form>

        <?php if($total > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($p=$pelanggan->fetch_assoc()): ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><strong><?=$p['nama']?></strong></td>
                    <td><?=$p['email']?></td>
                    <td><?=date('d M Y H:i', strtotime($p['created_at']))?></td>
                    <td><span class="badge">Aktif</span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty">Belum ada data pelanggan</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>