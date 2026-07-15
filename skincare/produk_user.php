<?php 
session_start();
require 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$produk = $conn->query("SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Produk - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;padding:0;box-sizing:border-box}
body{background:#FFF5F7}
.navbar{background:#fff; padding:18px 60px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 8px rgba(0,0,0,0.05); position:sticky; top:0;}
.logo{color:#E91E63; font-size:22px; font-weight:700; text-decoration:none;}
.menu a{margin:0 15px; text-decoration:none; color:#555; font-weight:500;}
.menu a.active{color:#E91E63; font-weight:600;}
.navbar-right{display:flex; align-items:center; gap:20px;}
.navbar-right a{color:#E91E63; text-decoration:none; font-weight:600;}
.icon-cart{font-size:24px; text-decoration:none;}
.container{padding:40px 60px;}
h2{margin-bottom:25px; color:#333;}
.produk-grid{display:grid; grid-template-columns:repeat(4, 1fr); gap:20px;}
.card{background:#fff; border-radius:12px; padding:15px; box-shadow:0 2px 10px rgba(0,0,0,0.08); transition:0.3s;}
.card:hover{transform:translateY(-5px);}
.card img{width:100%; height:200px; object-fit:cover; border-radius:8px;}
.card h3{font-size:15px; margin:12px 0 5px; color:#333; min-height:40px;}
.card .harga{color:#E91E63; font-size:18px; font-weight:700; margin:8px 0;}
.card .stok{font-size:12px; color:#666; margin-bottom:10px;}
.btn-cart{display:block; background:#E91E63; color:#fff; text-align:center; padding:12px; border-radius:10px; text-decoration:none; font-weight:600; transition:0.3s;}
.btn-cart:hover{background:#C2185B;}
</style>
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">SKINCARE SHOP</a>
    <div class="menu">
        <a href="index.php">BERANDA</a>
        <a href="produk_user.php" class="active">PRODUK</a>
        <a href="#">KATEGORI</a>
        <a href="#">PROMO</a>
    </div>
    <div class="navbar-right">
        <a href="keranjang.php" class="icon-cart">🛒</a>
        <span>Halo, <?=$_SESSION['user']['nama']?> | <a href="logout.php">Logout</a></span>
    </div>
</div>

<div class="container">
    <h2>Semua Produk</h2>
    <div class="produk-grid">
    <?php while($p = $produk->fetch_assoc()): ?>
        <div class="card">
            <img src="<?=$p['gambar']?>" alt="<?=$p['nama_produk']?>">
            <h3><?=$p['nama_produk']?></h3>
            <p class="harga">Rp<?=number_format($p['harga'])?></p>
            <p class="stok">Stok: <?=$p['stok']?> | Terjual: 0</p>
            <a href="keranjang.php?add=<?=$p['id']?>" class="btn-cart">+ Keranjang</a>
        </div>
    <?php endwhile; ?>
    </div>
</div>

</body>
</html>