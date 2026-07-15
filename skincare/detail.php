<?php require 'config/db.php'; cekLogin();
$id=$_GET['id']; $p=$conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();
if(isset($_POST['cart'])){
    $uid=$_SESSION['user']['id'];
    $conn->query("INSERT INTO keranjang (id_user,id_produk) VALUES ($uid,$id)");
    header("Location: keranjang.php");
}
?>
<!DOCTYPE html><html><head><title>Detail</title>
<style>*{font-family:Poppins,sans-serif}body{background:#FFF5F7;padding:40px 60px}
.box{display:flex;gap:40px;background:#fff;padding:30px;border-radius:16px}
.box img{width:350px;border-radius:12px}
h2{margin-bottom:10px} h1{color:#E91E63;margin:15px 0}
.btn{background:#E91E63;color:#fff;border:none;padding:12px 30px;border-radius:10px;cursor:pointer;font-weight:600}
.qty{display:flex;align-items:center;gap:10px;margin:15px 0}
.qty button{width:30px;height:30px;border:1px solid #E91E63;background:#fff;border-radius:5px}
</style></head><body>
<div class="box">
  <img src="assets/uploads/<?=$p['gambar']?:'noimg.jpg'?>">
  <div>
    <p style="color:#999;font-size:13px">Beranda > Serum > <?=$p['nama_produk']?></p>
    <h2><?=$p['nama_produk']?></h2>
    <p>⭐ <?=$p['rating']?> (98 ulasan) | Terjual <?=$p['terjual']?>+</p>
    <h1>Rp<?=number_format($p['harga'])?> <span style="font-size:16px;color:#999;text-decoration:line-through">Rp109.000</span></h1>
    <p style="color:#4CAF50">Stok: <?=$p['stok']?></p>
    <p><?=$p['deskripsi']?></p>
    <div class="qty">
      Jumlah <button>-</button> <span>1</span> <button>+</button>
    </div>
    <form method="post"><button name="cart" class="btn">TAMBAH KE KERANJANG</button></form>
  </div>
</div></body></html>