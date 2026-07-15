<?php 
session_start();
require 'db.php'; 

// Cek login
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
$uid = $_SESSION['user']['id'];

// 1. TAMBAH KE KERANJANG
if(isset($_GET['add'])){
    $id_produk = (int)$_GET['add'];
    
    // Cek udah ada belum
    $cek = $conn->query("SELECT * FROM keranjang WHERE id_user=$uid AND id_produk=$id_produk");
    if($cek && $cek->num_rows > 0){
        $conn->query("UPDATE keranjang SET jumlah=jumlah+1 WHERE id_user=$uid AND id_produk=$id_produk");
    } else {
        $conn->query("INSERT INTO keranjang(id_user, id_produk, jumlah) VALUES($uid, $id_produk, 1)");
    }
    header("Location: keranjang.php");
    exit;
}

// 2. HAPUS ITEM
if(isset($_GET['del'])) {
    $id_keranjang = (int)$_GET['del'];
    $conn->query("DELETE FROM keranjang WHERE id=$id_keranjang AND id_user=$uid");
    header("Location: keranjang.php");
    exit;
}

// 3. AMBIL DATA DENGAN CEK ERROR
$sql = "SELECT k.id as id_keranjang, k.jumlah, p.id, p.nama_produk, p.harga, p.gambar 
        FROM keranjang k 
        JOIN produk p ON k.id_produk = p.id 
        WHERE k.id_user = $uid";

$cart = $conn->query($sql);

// Kalau query error, tampilkan pesannya
if(!$cart){
    die("ERROR SQL: " . $conn->error . "<br>Query: " . $sql);
}

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Keranjang</title>
<style>
*{font-family:Poppins,sans-serif}body{background:#FFF5F7;padding:40px 60px}
.navbar{background:#fff;padding:18px 60px;margin:-40px -60px 30px}
.navbar a{color:#E91E63;text-decoration:none;font-weight:600}
table{width:100%;background:#fff;border-radius:12px;padding:20px;border-collapse:collapse;box-shadow:0 2px 10px rgba(0,0,0,0.08)}
th{text-align:left;padding:12px;border-bottom:2px solid #F8BBD0}
td{padding:12px;border-bottom:1px solid #eee}
td img{width:60px;height:60px;object-fit:cover;border-radius:8px;margin-right:10px;vertical-align:middle}
.btn{background:#E91E63;color:#fff;padding:12px 30px;border-radius:10px;text-decoration:none;font-weight:600;float:right;margin-top:20px}
.kosong{text-align:center;padding:50px;background:#fff;border-radius:12px}
</style>
</head>
<body>
<div class="navbar"><a href="produk_user.php">← Lanjut Belanja</a></div>

<h2>Keranjang Belanja</h2>

<?php if($cart->num_rows == 0): ?>
    <div class="kosong">
        <h3>Keranjang masih kosong 😢</h3>
    </div>
<?php else: ?>
<table>
<tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr>
<?php while($c = $cart->fetch_assoc()): 
    $sub = $c['harga'] * $c['jumlah']; 
    $total += $sub;
?>
<tr>
  <td><img src="<?=$c['gambar']?>"><?=$c['nama_produk']?></td>
  <td>Rp<?=number_format($c['harga'])?></td>
  <td><?=$c['jumlah']?></td>
  <td>Rp<?=number_format($sub)?></td>
  <td><a href="?del=<?=$c['id_keranjang']?>" onclick="return confirm('Hapus?')">🗑️</a></td>
</tr>
<?php endwhile;?>
</table>
<h3 style="text-align:right;margin-top:20px">Total Belanja: Rp<?=number_format($total)?></h3>
<a href="checkout.php" class="btn">LANJUT CHECKOUT</a>
<?php endif; ?>
</body></html>