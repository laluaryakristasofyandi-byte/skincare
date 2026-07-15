<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!='admin') header("Location: login.php");

if(isset($_POST['tambah'])) $conn->query("INSERT INTO kategori (nama_kategori) VALUES ('$_POST[nama]')");
if(isset($_GET['hapus'])) $conn->query("DELETE FROM kategori WHERE id=$_GET[hapus]");
if(isset($_POST['edit'])) $conn->query("UPDATE kategori SET nama_kategori='$_POST[nama]' WHERE id=$_POST[id]");

$kategori = $conn->query("SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html>
<head>
<title>Kelola Kategori</title>
<style>*{font-family:Poppins,sans-serif}body{display:flex;background:#FFF5F7}
.sidebar{width:250px;background:#2C3E50;color:#fff;min-height:100vh;padding:25px 0}
.sidebar a{display:block;color:#ecf0f1;padding:14px 25px;text-decoration:none;margin:5px 15px;border-radius:8px}
.active{background:#E91E63}
.content{flex:1;padding:30px}
table{width:100%;background:#fff;border-radius:12px;padding:20px;border-collapse:collapse}
.btn{background:#E91E63;color:#fff;border:none;padding:8px 15px;border-radius:6px;cursor:pointer}
input{padding:10px;border:1.5px solid #F8BBD0;border-radius:8px}
</style>
</head>
<body>
<div class="sidebar">
  <h2 style="text-align:center;color:#E91E63">ADMIN</h2>
  <a href="admin.php">Dashboard</a>
  <a href="admin_produk.php">Produk</a>
  <a href="admin_kategori.php" class="active">Kategori</a>
  <a href="admin_pesanan.php">Pesanan</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
  <h2>Kelola Kategori</h2>
  <form method="post" style="margin:20px 0">
    <input name="nama" placeholder="Nama Kategori Baru" required>
    <button name="tambah" class="btn">+ Tambah</button>
  </form>

  <table>
    <tr><th>ID</th><th>Nama Kategori</th><th>Aksi</th></tr>
    <?php while($k=$kategori->fetch_assoc()):?>
    <tr>
      <td><?=$k['id']?></td>
      <td>
        <form method="post" style="display:flex;gap:10px">
          <input type="hidden" name="id" value="<?=$k['id']?>">
          <input name="nama" value="<?=$k['nama_kategori']?>">
          <button name="edit" class="btn">Update</button>
        </form>
      </td>
      <td><a href="?hapus=<?=$k['id']?>"><button class="btn" style="background:#f44336">Hapus</button></a></td>
    </tr>
    <?php endwhile;?>
  </table>
</div>
</body>
</html>