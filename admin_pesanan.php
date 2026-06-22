<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!='admin') header("Location: login.php");

if(isset($_GET['status'])){
    $conn->query("UPDATE pesanan SET status='$_GET[status]' WHERE id=$_GET[id]");
}

$pesanan = $conn->query("SELECT p.*,u.nama FROM pesanan p JOIN users u ON p.id_user=u.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Kelola Pesanan</title>
<style>*{font-family:Poppins,sans-serif}body{display:flex;background:#FFF5F7}
.sidebar{width:250px;background:#2C3E50;color:#fff;min-height:100vh;padding:25px 0}
.sidebar a{display:block;color:#ecf0f1;padding:14px 25px;text-decoration:none;margin:5px 15px;border-radius:8px}
.active{background:#E91E63}
.content{flex:1;padding:30px}
table{width:100%;background:#fff;border-radius:12px;padding:20px;border-collapse:collapse}
.btn{padding:6px 12px;border:none;border-radius:6px;cursor:pointer;color:#fff}
</style>
</head>
<body>
<div class="sidebar">
  <h2 style="text-align:center;color:#E91E63">ADMIN</h2>
  <a href="admin.php">Dashboard</a>
  <a href="admin_produk.php">Produk</a>
  <a href="admin_kategori.php">Kategori</a>
  <a href="admin_pesanan.php" class="active">Pesanan</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
  <h2>Kelola Pesanan</h2>
  <table>
    <tr><th>ID</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
    <?php while($p=$pesanan->fetch_assoc()):?>
    <tr>
      <td>#<?=$p['id']?></td>
      <td><?=$p['nama']?></td>
      <td>Rp<?=number_format($p['total_bayar'])?></td>
      <td><span style="padding:5px 10px;border-radius:5px;background:<?= $p['status']=='Selesai'?'#4CAF50':'#FF9800'?>;color:#fff"><?=$p['status']?></span></td>
      <td>
        <a href="?id=<?=$p['id']?>&status=Diproses"><button class="btn" style="background:#2196F3">Proses</button></a>
        <a href="?id=<?=$p['id']?>&status=Selesai"><button class="btn" style="background:#4CAF50">Selesai</button></a>
      </td>
    </tr>
    <?php endwhile;?>
  </table>
</div>
</body>
</html>