<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login.php");

if(isset($_POST['update_status'])){
    $id = $_POST['id'];
    $status = $_POST['status'];
    $conn->query("UPDATE pesanan SET status='$status' WHERE id=$id");
    header("Location: pesanan.php");
}

$pesanan = $conn->query("SELECT p.*,u.nama FROM pesanan p JOIN users u ON p.id_user=u.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Data Pesanan</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
body { background:#fff5f7; display:flex; }
.sidebar { width:250px; background:#1e2a3a; color:white; height:100vh; position:fixed; padding:20px 0; }
.sidebar h2 { text-align:center; color:#ff4081; margin-bottom:30px; }
.sidebar a { display:block; padding:15px 25px; color:#ddd; text-decoration:none; border-left:4px solid transparent; }
.sidebar a:hover, .sidebar a.active { background:#2c3e50; color:#ff4081; border-left:4px solid #ff4081; }
.main { margin-left:250px; flex:1; padding:30px; }
.box { background:white; padding:25px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.08); }
.btn { padding:6px 12px; border:none; border-radius:5px; cursor:pointer; font-size:13px; }
.btn-pink { background:#ff4081; color:white; }
.status-diproses { color:#ff9800; font-weight:bold; }
.status-selesai { color:#4caf50; font-weight:bold; }
.status-dikirim { color:#2196f3; font-weight:bold; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th { background:#f5f5f5; padding:12px; text-align:left; }
td { padding:12px; border-bottom:1px solid #eee; }
select { padding:5px; border:1px solid #ddd; border-radius:4px; }
</style>
</head>
<body>
<div class="sidebar">
    <h2>SKINCARE SHOP</h2>
    <a href="admin.php">Dashboard</a>
    <a href="produk.php">Produk</a>
    <a href="kategori.php">Kategori</a>
    <a href="pelanggan.php">Pelanggan</a>
    <a href="pesanan.php" class="active">Pesanan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h1>Data Pesanan</h1>
    
    <div class="box">
        <table>
            <tr>
                <th>ID</th><th>Pelanggan</th><th>Total Bayar</th><th>Status</th><th>Tanggal</th><th>Update</th>
            </tr>
            <?php while($p=$pesanan->fetch_assoc()): ?>
            <tr>
                <td>#<?=$p['id']?></td>
                <td><?=$p['nama']?></td>
                <td>Rp<?=number_format($p['total_bayar'])?></td>
                <td class="status-<?=strtolower($p['status'])?>"><?=$p['status']?></td>
                <td><?=$p['created_at']?></td>
                <td>
                    <form method="POST" style="display:flex; gap:5px;">
                        <input type="hidden" name="id" value="<?=$p['id']?>">
                        <select name="status">
                            <option <?=($p['status']=='Diproses')?'selected':''?>>Diproses</option>
                            <option <?=($p['status']=='Dikirim')?'selected':''?>>Dikirim</option>
                            <option <?=($p['status']=='Selesai')?'selected':''?>>Selesai</option>
                            <option <?=($p['status']=='Batal')?'selected':''?>>Batal</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-pink">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>