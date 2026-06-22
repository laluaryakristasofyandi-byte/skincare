<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Filter kategori kalau dari klik kategori
$where = "WHERE p.stok > 0";
if(isset($_GET['kat'])){
    $kat = (int)$_GET['kat'];
    $where .= " AND p.id_kategori = $kat";
}

// Ambil produk dari tabel yang sama kayak admin
$produk = $conn->query("SELECT p.*,k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori=k.id $where ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Produk - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;box-sizing:border-box}
body{background:#FFF5F7}
.navbar{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.05);position:sticky;top:0;z-index:99}
.logo{color:#E91E63;font-weight:700;font-size:22px}
.nav a{margin:0 18px;color:#555;text-decoration:none;font-size:14px;font-weight:500}
.nav a:hover,.nav a.active{color:#E91E63}
.nav-right{display:flex;align-items:center;gap:20px}
.container{padding:40px 60px;min-height:60vh}
.container h1{font-size:28px;color:#333;margin-bottom:25px}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:25px}
.card{background:#fff;padding:20px;border-radius:15px;box-shadow:0 3px 15px rgba(0,0,0,0.06);transition:0.3s}
.card:hover{transform:translateY(-5px)}
.card img{width:100%;height:200px;object-fit:cover;border-radius:10px;margin-bottom:12px}
.card h3{color:#333;font-size:16px;margin:8px 0;height:40px;overflow:hidden}
.card p{color:#E91E63;font-weight:bold;font-size:20px}
.btn{background:#E91E63;color:#fff;padding:10px;border:none;border-radius:8px;width:100%;cursor:pointer;font-weight:600}
.btn:hover{background:#c2185b}
footer{background:#1e2a3a;color:white;padding:40px 60px;margin-top:50px}
.footer-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:40px}
footer h4{color:#E91E63;margin-bottom:15px}
footer p{color:#ddd;font-size:14px;margin:8px 0}
.copy{text-align:center;margin-top:30px;padding-top:20px;border-top:1px solid #444;color:#999;font-size:13px}
</style>
</head>
<body>
<div class="navbar">
  <div class="logo">SKINCARE SHOP</div>
  <div class="nav">
    <a href="index.php">BERANDA</a>
    <a href="produk.php" class="active">PRODUK</a>
    <a href="kategori.php">KATEGORI</a>
    <a href="promo.php">PROMO</a>
  </div>
  <div class="nav-right">
    <span>🛒</span>
    <span>Halo, <?=$user['nama']?> | <a href="logout.php" style="color:#E91E63">Logout</a></span>
  </div>
</div>

<div class="container">
  <h1><?=isset($_GET['kat'])? 'Produk Kategori' : 'Semua Produk'?></h1>
  <div class="grid">
    <?php if($produk->num_rows > 0): while($p=$produk->fetch_assoc()):?>
    <div class="card">
      <?php if(!empty($p['gambar']) && strlen($p['gambar'])>20): ?>
        <img src="<?=$p['gambar']?>">
      <?php else: ?>
        <img src="https://via.placeholder.com/200x200/E91E63/ffffff?text=No+Image">
      <?php endif; ?>
      <h3><?=$p['nama_produk']?></h3>
      <small style="color:#999"><?=$p['nama_kategori']?></small>
      <p>Rp<?=number_format($p['harga'])?></p>
      <small>Stok: <?=$p['stok']?> | Terjual: <?=$p['terjual']?></small>
      <button class="btn" style="margin-top:10px">+ Keranjang</button>
    </div>
    <?php endwhile; else:?>
    <p style="grid-column:1/-1;text-align:center;color:#999;padding:50px">Belum ada produk. Tambah di admin dulu ya.</p>
    <?php endif;?>
  </div>
</div>

<footer>
  <div class="footer-grid">
    <div>
      <h4>SKINCARE SHOP</h4>
      <p>Skincare original 100% BPOM. Kirim seluruh Indonesia.</p>
    </div>
    <div>
      <h4>Kontak Kami</h4>
      <p>📍 Mataram, NTB</p>
      <p>📞 0812-3456-7890</p>
      <p>✉️ cs@skincareshop.com</p>
      <p>🕒 08:00 - 21:00 WITA</p>
    </div>
    <div>
      <h4>Menu</h4>
      <a href="index.php">Beranda</a>
      <a href="produk.php">Produk</a>
      <a href="kategori.php">Kategori</a>
    </div>
  </div>
  <div class="copy">© 2026 Skincare Shop</div>
</footer>
</body>
</html>