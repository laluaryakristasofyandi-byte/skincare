<?php 
session_start();
require 'db.php';

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit; }
$uid = $_SESSION['user']['id'];
$nama = $_SESSION['user']['nama'];

$sql = "SELECT k.*, p.nama_produk, p.harga FROM keranjang k JOIN produk p ON k.id_produk = p.id WHERE k.id_user = $uid";
$cart = $conn->query($sql);
if($cart->num_rows == 0){ echo "<script>alert('Keranjang kosong!');window.location='produk_user.php';</script>"; exit; }

// PROSES CHECKOUT
if(isset($_POST['bayar'])){
    $kode = 'SKN-' . date('Ymd') . '-' . rand(100,999);
    $nama_penerima = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $metode = $_POST['metode'];
    $total = $_POST['total'];

    $conn->query("INSERT INTO pesanan(kode_pesanan, id_user, nama_penerima, no_hp, alamat, total_bayar, metode_bayar) 
                  VALUES('$kode', $uid, '$nama_penerima', '$no_hp', '$alamat', $total, '$metode')");
    $id_pesanan = $conn->insert_id;

    $cart2 = $conn->query($sql); // ambil lagi
    while($c = $cart2->fetch_assoc()){
        $conn->query("INSERT INTO detail_pesanan(id_pesanan, id_produk, jumlah, harga) 
                      VALUES($id_pesanan, {$c['id_produk']}, {$c['jumlah']}, {$c['harga']})");
    }
    $conn->query("DELETE FROM keranjang WHERE id_user=$uid");
    echo "<script>alert('Pesanan #$kode berhasil dibuat!');window.location='riwayat.php';</script>"; exit;
}

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Checkout - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif}body{background:#FFF5F7;padding:40px 60px}
.navbar{background:#fff;padding:18px 60px;margin:-40px -60px 30px}
.navbar a{color:#E91E63;text-decoration:none;font-weight:600}
.box{background:#fff;padding:25px;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.08)}
h2{margin-bottom:15px;color:#333}
.grid{display:grid; grid-template-columns:2fr 1fr; gap:20px;}
label{font-weight:600;font-size:14px;display:block;margin-top:12px}
input,textarea,select{width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;margin-top:5px}
.btn{background:#E91E63;color:#fff;padding:14px;border:none;border-radius:10px;width:100%;font-weight:700;font-size:16px;cursor:pointer}
.btn:hover{background:#C2185B}
.metode{border:1px solid #ddd;padding:12px;border-radius:8px;margin-top:8px;cursor:pointer}
.metode input{width:auto;margin-right:10px}
table{width:100%} td{padding:8px 0} td:last-child{text-align:right}
</style>
</head>
<body>
<div class="navbar"><a href="keranjang.php">← Kembali ke Keranjang</a></div>
<h2>Checkout</h2>
<div class="grid">
    <div class="box">
        <h3>Alamat Pengiriman</h3>
        <form method="POST">
            <label>Nama Penerima</label><input type="text" name="nama" value="<?=$nama?>" required>
            <label>No HP</label><input type="text" name="no_hp" required>
            <label>Alamat Lengkap</label><textarea name="alamat" rows="3" required></textarea>

            <h3 style="margin-top:20px">Metode Pembayaran</h3>
            <div class="metode"><label><input type="radio" name="metode" value="Transfer BCA" checked> Transfer Bank BCA - 1234567890 a.n Skincare Shop</label></div>
            <div class="metode"><label><input type="radio" name="metode" value="Transfer Mandiri"> Transfer Bank Mandiri - 0987654321 a.n Skincare Shop</label></div>
            <div class="metode"><label><input type="radio" name="metode" value="COD"> Bayar di Tempat / COD</label></div>
            
            <button type="submit" name="bayar" class="btn" style="margin-top:20px">BUAT PESAN</button>
    </div>

    <div class="box">
        <h3>Ringkasan Pesanan</h3>
        <table>
        <?php while($c = $cart->fetch_assoc()): $sub = $c['harga'] * $c['jumlah']; $total += $sub; ?>
        <tr><td><?=$c['nama_produk']?> x <?=$c['jumlah']?></td><td>Rp<?=number_format($sub)?></td></tr>
        <?php endwhile; ?>
        <tr style="border-top:2px solid #eee; font-weight:700"><td>Total</td><td>Rp<?=number_format($total)?></td></tr>
        </table>
        <input type="hidden" name="total" value="<?=$total?>">
        </form>
    </div>
</div>
</body></html>