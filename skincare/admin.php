<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

// Cek login admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

// Ambil data real dari DB
$total_produk = $conn->query("SELECT COUNT(*) as jml FROM produk")->fetch_assoc()['jml'] ?? 0;
$total_pesanan = $conn->query("SELECT COUNT(*) as jml FROM pesanan")->fetch_assoc()['jml'] ?? 0;
$total_pelanggan = $conn->query("SELECT COUNT(*) as jml FROM users WHERE role='user'")->fetch_assoc()['jml'] ?? 0;
$pendapatan = $conn->query("SELECT SUM(total_bayar) as total FROM pesanan WHERE status='Selesai'")->fetch_assoc()['total'] ?? 0;

// Produk terlaris
$produk_terlaris = $conn->query("SELECT * FROM produk ORDER BY terjual DESC LIMIT 5");
$nama_admin = $_SESSION['user']['nama'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Skincare Shop</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, sans-serif; }
body { background: #fff5f7; display: flex; min-height: 100vh; }

/* Sidebar */
.sidebar { 
    width: 250px; 
    background: #1e2a3a; 
    color: white; 
    position: fixed; 
    height: 100vh; 
    padding: 20px 0;
}
.sidebar h2 { 
    text-align: center; 
    color: #ff4081; 
    margin-bottom: 30px; 
    font-size: 24px;
}
.sidebar a { 
    display: block; 
    padding: 15px 25px; 
    color: #ddd; 
    text-decoration: none; 
    transition: 0.3s;
    border-left: 4px solid transparent;
}
.sidebar a:hover, .sidebar a.active { 
    background: #2c3e50; 
    color: #ff4081; 
    border-left: 4px solid #ff4081;
}

/* Main Content */
.main { 
    margin-left: 250px; 
    flex: 1; 
    padding: 30px;
}
.header { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 30px;
}
.header h1 { color: #333; }
.header span { color: #666; font-size: 18px; }

/* Card Grid */
.card-grid { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 20px; 
    margin-bottom: 30px;
}
.card { 
    background: white; 
    padding: 25px; 
    border-radius: 12px; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}
.card:hover { transform: translateY(-5px); }
.card-label { color: #999; font-size: 14px; margin-bottom: 8px; }
.card-value { font-size: 32px; font-weight: bold; }
.card-value.pink { color: #ff4081; }
.card-value.green { color: #4caf50; }
.card-value.blue { color: #2196f3; }
.card-value.orange { color: #ff9800; }

/* Box Produk Terlaris */
.box { 
    background: white; 
    padding: 25px; 
    border-radius: 12px; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.box h3 { margin-bottom: 15px; color: #333; }
.box p { color: #999; }

/* Responsive */
@media(max-width: 1024px){
    .card-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 768px){
    .sidebar { width: 200px; }
    .main { margin-left: 200px; }
    .card-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>SKINCARE SHOP</h2>
    <a href="admin.php" class="active">Dashboard</a>
    <a href="produk.php">Produk</a>
    <a href="kategori.php">Kategori</a>
    <a href="pelanggan.php">Pelanggan</a>
    <a href="pesanan.php">Pesanan</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="main">
    <div class="header">
        <h1>Dashboard</h1>
        <span>Halo, <?=htmlspecialchars($nama_admin)?> 👑</span>
    </div>

    <!-- Card Statistik -->
    <div class="card-grid">
        <div class="card">
            <div class="card-label">Total Produk</div>
            <div class="card-value pink"><?=number_format($total_produk)?></div>
        </div>
        <div class="card">
            <div class="card-label">Total Pesanan</div>
            <div class="card-value green"><?=number_format($total_pesanan)?></div>
        </div>
        <div class="card">
            <div class="card-label">Total Pelanggan</div>
            <div class="card-value blue"><?=number_format($total_pelanggan)?></div>
        </div>
        <div class="card">
            <div class="card-label">Pendapatan</div>
            <div class="card-value orange">Rp<?=number_format($pendapatan)?></div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="box">
        <h3>Produk Terlaris</h3>
        <?php if($produk_terlaris->num_rows > 0): ?>
            <table width="100%" style="border-collapse: collapse; margin-top: 10px;">
                <tr style="background: #f5f5f5;">
                    <th style="padding: 10px; text-align: left;">Nama Produk</th>
                    <th style="padding: 10px; text-align: left;">Terjual</th>
                    <th style="padding: 10px; text-align: left;">Stok</th>
                </tr>
                <?php while($p = $produk_terlaris->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;"><?=$p['nama_produk']?></td>
                    <td style="padding: 10px;"><?=$p['terjual']?></td>
                    <td style="padding: 10px;"><?=$p['stok']?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Data produk akan muncul di sini...</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>