<?php
session_start();
require 'db.php';

// 1. HAPUS CEK ADMIN. Biar pembeli & guest bisa liat
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

$kategori = $conn->query("SELECT * FROM kategori LIMIT 4");
$produk = $conn->query("SELECT p.*,k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori=k.id WHERE p.stok>0 ORDER BY p.terjual DESC LIMIT 8");
?>
<!DOCTYPE html>
<html>
<head>
<title>Beranda - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;box-sizing:border-box}
body{background:#FFF5F7}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.05);position:sticky;top:0;z-index:99}
.logo{color:#E91E63;font-weight:700;font-size:22px}
.nav a{margin:0 18px;color:#555;text-decoration:none;font-size:14px;font-weight:500}
.nav a:hover,.nav a.active{color:#E91E63}
.nav-right{display:flex;align-items:center;gap:20px}
.search{padding:10px 18px;border:1.5px solid #F8BBD0;border-radius:25px;width:280px;outline:none}
.banner{background:linear-gradient(135deg,#FCE4EC,#F8BBD0);padding:70px 60px;margin:30px 60px;border-radius:18px;display:flex;align-items:center;justify-content:space-between}
.banner h1{font-size:42px;color:#333;line-height:1.3}
.banner p{color:#666;margin:15px 0;font-size:16px}
.btn{background:#E91E63;color:#fff;padding:14px 32px;border-radius:10px;text-decoration:none;font-weight:600;display:inline-block;margin-top:10px}
.btn:hover{background:#c2185b}
.section{padding:40px 60px}
.section h3{font-size:24px;margin-bottom:25px;color:#333}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:25px}
.card{background:#fff;padding:20px;border-radius:15px;text-align:center;box-shadow:0 3px 15px rgba(0,0,0,0.06);transition:0.3s;text-decoration:none;display:block}
.card:hover{transform:translateY(-5px);box-shadow:0 5px 20px rgba(233,30,99,0.2)}
.card img{width:100%;height:180px;object-fit:cover;border-radius:10px;margin-bottom:10px}
.card h4{color:#333;margin:10px 0;font-size:15px}
.card p{color:#E91E63;font-weight:bold;font-size:18px}
footer{background:#1e2a3a;color:white;padding:40px 60px;margin-top:50px}
.footer-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:40px}
footer h4{color:#E91E63;margin-bottom:15px;font-size:18px}
footer p,footer a{color:#ddd;font-size:14px;margin:8px 0;display:block;text-decoration:none;line-height:1.6}
footer a:hover{color:#E91E63}
.copy{text-align:center;margin-top:30px;padding-top:20px;border-top:1px solid #444;color:#999;font-size:13px}
</style>
</head>
<body>
<div class="navbar">
  <div class="logo">SKINCARE SHOP</div>
  <div class="nav">
    <a href="index.php" class="active">BERANDA</a>
    <a href="produk_user.php">PRODUK</a>  <!-- UDAH DIGANTI -->
    <a href="kategori.php">KATEGORI</a>
    <a href="promo.php">PROMO</a>
  </div>
  <div class="nav-right">
    <input class="search" placeholder="Cari produk skincare...">
    <a href="keranjang.php" style="text-decoration:none">🛒</a>
    <span>
      <?php if($user): ?>
        Halo, <?=$user['nama']?> | <a href="logout.php" style="color:#E91E63">Logout</a>
      <?php else: ?>
        <a href="login.php" style="color:#E91E63">Login</a> | <a href="register.php" style="color:#E91E63">Daftar</a>
      <?php endif; ?>
    </span>
  </div>
</div>

<div class="banner">
  <div>
    <h1>Kulit Sehat Adalah <br> Investasi Terbaikmu ✨</h1>
    <p>Rawat kulitmu setiap hari dengan produk skincare terpercaya untuk semua jenis kulit</p>
    <a href="produk_user.php" class="btn">BELANJA SEKARANG</a>
  </div>
  <img src="https://via.placeholder.com/320x300/E91E63/ffffff?text=Skincare" width="320" style="border-radius:15px">
</div>

<div class="section">
  <h3>Kategori Populer</h3>
  <div class="grid">
    <?php while($k=$kategori->fetch_assoc()):?>
    <a href="produk_user.php?kat=<?=$k['id']?>" class="card"> <!-- UDAH DIGANTI -->
      <h4><?=$k['nama_kategori']?></h4>
    </a>
    <?php endwhile;?>
  </div>
</div>

<div class="section">
  <h3>Produk Terlaris</h3>
  <div class="grid">
    <?php if($produk->num_rows > 0): while($p=$produk->fetch_assoc()):?>
    <div class="card">
      <img src="<?=!empty($p['gambar'])? $p['gambar'] : 'https://via.placeholder.com/180x180?text=No+Image'?>">
      <h4><?=$p['nama_produk']?></h4>
      <p>Rp<?=number_format($p['harga'])?></p>
    </div>
    <?php endwhile; else:?>
    <p style="grid-column:1/-1;text-align:center;color:#999">Belum ada produk</p>
    <?php endif;?>
  </div>
</div>

<footer>
  <div class="footer-grid">
    <div>
      <h4>SKINCARE SHOP</h4>
      <p>Toko skincare terpercaya 100% original & BPOM. Melayani seluruh Indonesia.</p>
    </div>
    <div>
      <h4>Kontak Kami</h4>
      <p>📍 Mataram, Nusa Tenggara Barat</p>
      <p>📞 0812-3456-7890</p>
      <p>✉️ cs@skincareshop.com</p>
    </div>
    <div>
      <h4>Menu Cepat</h4>
      <a href="index.php">Beranda</a>
      <a href="produk_user.php">Produk</a> <!-- UDAH DIGANTI -->
      <a href="kategori.php">Kategori</a>
    </div>
  </div>
  <div class="copy">© 2026 Skincare Shop</div>
</footer>
</body>
</html>