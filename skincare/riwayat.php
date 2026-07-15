<?php 
session_start();
require 'db.php';
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
$uid = $_SESSION['user']['id'];

$pesanan = $conn->query("SELECT * FROM pesanan WHERE id_user=$uid ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Riwayat Pesanan - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0}
body{background:#FFF5F7}
.navbar{background:#fff;padding:18px 60px;display:flex;justify-content:space-between;box-shadow:0 2px 8px rgba(0,0,0,0.05)}
.navbar a{color:#E91E63;text-decoration:none;font-weight:600}
.container{padding:40px 60px}
.box{background:#fff;padding:20px;border-radius:12px;margin-bottom:15px;box-shadow:0 2px 10px rgba(0,0,0,0.08)}
.status{padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600;background:#D4EDDA;color:#155724}
.kode{color:#E91E63;font-weight:700;font-size:16px}
</style>
</head>
<body>
<div class="navbar">
    <a href="produk_user.php">← Lanjut Belanja</a>
    <a href="keranjang.php">🛒 Keranjang</a>
</div>

<div class="container">
<h2>Riwayat Pesanan</h2>

<?php if($pesanan->num_rows == 0): ?>
    <div class="box">Belum ada pesanan. <a href="produk_user.php">Belanja sekarang</a></div>
<?php endif; ?>

<?php while($p = $pesanan->fetch_assoc()): ?>
<div class="box">
    <div style="display:flex; justify-content:space-between; margin-bottom:10px">
        <div><span class="kode">#<?=$p['kode_pesanan']?></span> - <?=date('d M Y H:i', strtotime($p['tanggal']))?></div>
        <span class="status"><?=$p['status']?></span>
    </div>
    <div><b>Total:</b> Rp<?=number_format($p['total_bayar'])?></div>
    <div><b>Bayar via:</b> <?=$p['metode_bayar']?></div>
    <div><b>Alamat:</b> <?=$p['alamat']?></div>
</div>
<?php endwhile; ?>
</div>
</body></html>