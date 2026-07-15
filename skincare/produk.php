<?php
session_start();
require 'db.php'; // pake db.php yang sama
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Filter kategori
$where = "WHERE p.stok > 0";
$judul = "Semua Produk";
if(isset($_GET['kat'])){
    $kat = (int)$_GET['kat'];v
    $where .= " AND p.id_kategori = $kat";
    $cek = $conn->query("SELECT nama_kategori FROM kategori WHERE id=$kat");
    if($cek->num_rows > 0){
        $judul = "Kategori: " . $cek->fetch_assoc()['nama_kategori'];
    }
}

$produk = $conn->query("SELECT p.*,k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori=k.id $where ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$judul?> - Skincare Shop</title>
<style>
*{font-family:'Segoe UI',sans-serif;margin:0;box-sizing:border-box}
body{background:#fff5f7}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.05);position:sticky;top:0}
.logo{color:#ff4081;font-weight:700;font-size:22px}
.nav a{margin:0 18px;color:#555;text-decoration:none;font-weight:500}
.nav a.active{color:#ff4081}
.container{padding:40px 60px}
.container h1{font-size:28px;margin-bottom:30px;color:#333}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:25px}
.card{background:white;padding:20px;border-radius:12px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.08);transition:0.3s}
.card:hover{transform:translateY(-5px)}
.card img{width:100%;height:200px;object-fit:cover;border-radius:8px;margin-bottom:10px;border:2px solid #eee}
.card h4{color:#333;margin:10px 0;font-size:14px;height:40px;overflow:hidden}
.card p{color:#ff4081;font-weight:bold;font-size:18px;margin:10px 0}
.btn{background:#ff4081;color:white;padding:10px;border-radius:5px;text-decoration:none;display:block;font-weight:600}
.btn:hover{background:#e91e63}
.kosong{text-align:center;color:#999;font-size:16px;padding:50px}
</style>
</head>
<body>
<div class="navbar">
  <div class="logo">SKINCARE SHOP</div>
  <div class="nav">
    <a href="index.php">BERANDA</a>
    <a href="produk_user.php" class="active">PRODUK</a>
    <a href="kategori.php">KATEGORI</a>
    <a href="promo.php">PROMO</a>
  </div>
  <div>
    <?php if($user): ?>
        Halo, <?=$user['nama']?> | <a href="logout.php" style="color:#ff4081">Logout</a>
    <?php else: ?>
        <a href="login.php" style="color:#ff4081">Login</a>
    <?php endif; ?>
  </div>
</div>

<div class="container">
  <h1><?=$judul?></h1>
  
  <div class="grid">
    <?php if($produk->num_rows > 0): 
      while($p=$produk->fetch_assoc()):?>
      <div class="card">
        <img src="<?=!empty($p['gambar'])? $p['gambar'] : 'https://via.placeholder.com/200x200/ff4081/ffffff?text=No+Image'?>">
        <h4><?=$p['nama_produk']?></h4>
        <small style="color:#999"><?=$p['nama_kategori']?></small>
        <p>Rp<?=number_format($p['harga'])?></p>
        <small>Stok: <?=$p['stok']?></small><br><br>
        <a href="keranjang.php?add=<?=$p['id']?>" class="btn">+ Tambah Keranjang</a>
      </div>
    <?php endwhile; 
      else: ?>
      <div class="kosong" style="grid-column:1/-1">Produk tidak ditemukan 😢</div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>