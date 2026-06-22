<?php require 'config/db.php'; cekLogin();
$uid=$_SESSION['user']['id'];
if(isset($_GET['del'])) $conn->query("DELETE FROM keranjang WHERE id=$_GET[del]");
$cart=$conn->query("SELECT k.*,p.nama_produk,p.harga,p.gambar FROM keranjang k JOIN produk p ON k.id_produk=p.id WHERE k.id_user=$uid");
$total=0;
?>
<!DOCTYPE html><html><head><title>Keranjang</title>
<style>*{font-family:Poppins,sans-serif}body{background:#FFF5F7;padding:40px 60px}
table{width:100%;background:#fff;border-radius:12px;padding:20px;border-collapse:collapse}
th{text-align:left;padding:12px;border-bottom:2px solid #F8BBD0}
td{padding:12px;border-bottom:1px solid #eee}
.btn{background:#E91E63;color:#fff;padding:12px 30px;border-radius:10px;text-decoration:none;font-weight:600;float:right;margin-top:20px}
</style></head><body>
<h2>Keranjang Belanja</h2>
<table>
<tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr>
<?php while($c=$cart->fetch_assoc()): $sub=$c['harga']*$c['jumlah']; $total+=$sub;?>
<tr>
  <td><?=$c['nama_produk']?></td>
  <td>Rp<?=number_format($c['harga'])?></td>
  <td><?=$c['jumlah']?></td>
  <td>Rp<?=number_format($sub)?></td>
  <td><a href="?del=<?=$c['id']?>">🗑️</a></td>
</tr>
<?php endwhile;?>
</table>
<h3 style="text-align:right;margin-top:20px">Total Belanja: Rp<?=number_format($total)?></h3>
<a href="checkout.php" class="btn">LANJUT CHECKOUT</a>
</body></html>