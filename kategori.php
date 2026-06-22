<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login.php");

if(isset($_POST['simpan'])){
    $id = $_POST['id'];
    $nama = $_POST['nama_kategori'];
    if($id == ''){
        $conn->query("INSERT INTO kategori(nama_kategori) VALUES('$nama')");
    } else {
        $conn->query("UPDATE kategori SET nama_kategori='$nama' WHERE id=$id");
    }
    header("Location: kategori.php");
}

if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM kategori WHERE id=$id");
    header("Location: kategori.php");
}

$edit = null;
if(isset($_GET['edit'])){
    $edit = $conn->query("SELECT * FROM kategori WHERE id=".$_GET['edit'])->fetch_assoc();
}

$kategori = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Data Kategori</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
body { background:#fff5f7; display:flex; }
.sidebar { width:250px; background:#1e2a3a; color:white; height:100vh; position:fixed; padding:20px 0; }
.sidebar h2 { text-align:center; color:#ff4081; margin-bottom:30px; }
.sidebar a { display:block; padding:15px 25px; color:#ddd; text-decoration:none; border-left:4px solid transparent; }
.sidebar a:hover, .sidebar a.active { background:#2c3e50; color:#ff4081; border-left:4px solid #ff4081; }
.main { margin-left:250px; flex:1; padding:30px; }
.box { background:white; padding:25px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.08); margin-bottom:20px; }
.btn { padding:8px 15px; border:none; border-radius:5px; cursor:pointer; text-decoration:none; display:inline-block; font-size:14px; }
.btn-pink { background:#ff4081; color:white; }
.btn-red { background:#f44336; color:white; }
.btn-blue { background:#2196f3; color:white; }
input { padding:8px; border:1px solid #ddd; border-radius:5px; width:300px; margin:5px 0; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th { background:#f5f5f5; padding:12px; text-align:left; }
td { padding:12px; border-bottom:1px solid #eee; }
</style>
</head>
<body>
<div class="sidebar">
    <h2>SKINCARE SHOP</h2>
    <a href="admin.php">Dashboard</a>
    <a href="produk.php">Produk</a>
    <a href="kategori.php" class="active">Kategori</a>
    <a href="pelanggan.php">Pelanggan</a>
    <a href="pesanan.php">Pesanan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h1>Data Kategori</h1>
    
    <div class="box">
        <h3><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
            <input type="text" name="nama_kategori" placeholder="Nama Kategori" value="<?= $edit['nama_kategori'] ?? '' ?>" required>
            <button type="submit" name="simpan" class="btn btn-pink">Simpan</button>
            <?php if($edit): ?><a href="kategori.php" class="btn">Batal</a><?php endif; ?>
        </form>
    </div>

    <div class="box">
        <table>
            <tr><th>ID</th><th>Nama Kategori</th><th>Aksi</th></tr>
            <?php while($k=$kategori->fetch_assoc()): ?>
            <tr>
                <td><?=$k['id']?></td>
                <td><?=$k['nama_kategori']?></td>
                <td>
                    <a href="?edit=<?=$k['id']?>" class="btn btn-blue">Edit</a>
                    <a href="?hapus=<?=$k['id']?>" class="btn btn-red" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>