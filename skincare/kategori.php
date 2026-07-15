<?php
session_start();
require 'db.php';
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

$kategori = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Kategori - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;box-sizing:border-box}
body{background:#FFF5F7}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.05);position:sticky;top:0;z-index:99}
.logo{color:#E91E63;font-weight:700;font-size:22px}
.nav a{margin:0 18px;color:#555;text-decoration:none;font-size:14px;font-weight:500}
.nav a:hover,.nav a.active{color:#E91E63}
.container{padding:40px 60px}
.container h1{font-size:28px;margin-bottom:30px;color:#333;text-align:center}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:25px}
.card{background:#fff;padding:40px 20px;border-radius:15px;text-align:center;box-shadow:0 3px 15px rgba(0,0,0,0.06);transition:0.3s;text-decoration:none;display:block}
.card:hover{transform:translateY(-5px);box-shadow:0 5px 20px rgba(233,30,99,0.2)}
.card h4{color:#E91E63;font-size:20px;margin-bottom:10px}
.card p{color:#777;font-size:14px}
</style>
</head>
<body>
<div class="navbar">
  <div class="logo">SKINCARE SHOP</div>
  <div class="nav">
    <a href="index.php">BERANDA</a>
    <a href="produk_user.php">PRODUK</a>
    <a href="kategori.php" class="active">KATEGORI</a>
    <a href="promo.php">PROMO</a>
  </div>
  <div>
    <?php if($user): ?>
        Halo, <?=$user['nama']?> | <a href="logout.php" style="color:#E91E63">Logout</a>
    <?php else: ?>
        <a href="login.php" style="color:#E91E63">Login</a> | <a href="register.php" style="color:#E91E63">Daftar</a>
    <?php endif; ?>
  </div>
</div>

<div class="container">
  <h1>Pilih Kategori</h1>
  <div class="grid">
    <?php if($kategori->num_rows > 0): 
      while($k=$kategori->fetch_assoc()): 
        $jumlah = $conn->query("SELECT COUNT(*) as jml FROM produk WHERE id_kategori=".$k['id']." AND stok>0")->fetch_assoc()['jml'];
      ?>
      <a href="produk_user.php?kat=<?=$k['id']?>" class="card">
        <h4><?=$k['nama_kategori']?></h4>
        <p><?=$jumlah?> Produk Tersedia</p>
      </a>
    <?php endwhile; 
      else: ?>
      <p style="grid-column:1/-1;text-align:center;color:#999">Belum ada kategori</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>