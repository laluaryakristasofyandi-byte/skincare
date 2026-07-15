<?php
session_start();
require 'db.php';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

$promo = $conn->query("SELECT p.*,k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori=k.id WHERE p.stok>0 ORDER BY RAND() LIMIT 8");
?>
<!DOCTYPE html>
<html>
<head>
<title>Promo - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;box-sizing:border-box}
body{background:#FFF5F7}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.05)}
.logo{color:#E91E63;font-weight:700;font-size:22px}
.nav a{margin:0 18px;color:#555;text-decoration:none;font-weight:500}
.nav a.active{color:#E91E63}
.banner-promo{background:linear-gradient(135deg,#E91E63,#F06292);padding:60px;text-align:center;color:white}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:25px;padding:40px 60px}
.card{background:#fff;padding:20px;border-radius:15px;text-align:center;box-shadow:0 3px 15px rgba(0,0,0,0.06);position:relative}
.badge{position:absolute;top:10px;right:10px;background:#E91E63;color:white;padding:5px 10px;border-radius:20px;font-size:12px;font-weight:bold}
.card img{width:100%;height:180px;object-fit:cover;border-radius:10px}
.harga{color:#999;text-decoration:line-through}
.harga-promo{color:#E91E63;font-weight:bold;font-size:18px}
.btn{background:#E91E63;color:#fff;padding:10px;border-radius:8px;text-decoration:none;display:block;margin-top:10px}
</style>
</head>
<body>
<div class="navbar">
  <div class="logo">SKINCARE SHOP</div>
  <div class="nav">
    <a href="index.php">BERANDA</a>
    <a href="produk_user.php">PRODUK</a>
    <a href="kategori.php">KATEGORI</a>
    <a href="promo.php" class="active">PROMO</a>
  </div>
  <div>
    <?php if($user): ?>Halo, <?=$user['nama']?> | <a href="logout.php">Logout</a>
    <?php else: ?><a href="login.php">Login</a><?php endif; ?>
  </div>
</div>

<div class="banner-promo">
  <h1>🔥 FLASH SALE 20% 🔥</h1>
  <p>Diskon spesial produk pilihan hari ini!</p>
</div>

<div class="grid">
  <?php while($p=$promo->fetch_assoc()): 
    $harga_promo = $p['harga'] * 0.8; ?>
  <div class="card">
    <div class="badge">-20%</div>
    <img src="<?=!empty($p['gambar'])? $p['gambar'] : 'https://via.placeholder.com/180'?>">
    <h4><?=$p['nama_produk']?></h4>
    <div class="harga">Rp<?=number_format($p['harga'])?></div>
    <div class="harga-promo">Rp<?=number_format($harga_promo)?></div>
    <a href="keranjang.php?add=<?=$p['id']?>" class="btn">Beli Sekarang</a>
  </div>
  <?php endwhile; ?>
</div>
</body>
</html>