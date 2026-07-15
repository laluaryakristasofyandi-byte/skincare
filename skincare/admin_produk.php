<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!='admin') header("Location: login.php");

// TAMBAH PRODUK
if(isset($_POST['tambah'])){
    $nama = $_POST['nama'];
    $desc = $_POST['desc'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kat = $_POST['kategori'];

    // Upload foto jadi base64
    $gambar = "data:image/png;base64,". base64_encode(file_get_contents($_FILES['gambar']['tmp_name']));

    $conn->query("INSERT INTO produk (nama_produk,deskripsi,harga,stok,id_kategori,gambar)
    VALUES ('$nama','$desc','$harga','$stok','$kat','$gambar')");
    header("Location: admin_produk.php");
}

// HAPUS PRODUK
if(isset($_GET['hapus'])){
    $conn->query("DELETE FROM produk WHERE id=$_GET[hapus]");
    header("Location: admin_produk.php");
}

// EDIT PRODUK
if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $desc = $_POST['desc'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kat = $_POST['kategori'];

    $sql = "UPDATE produk SET nama_produk='$nama', deskripsi='$desc', harga='$harga', stok='$stok', id_kategori='$kat'";

    // kalau upload foto baru
    if($_FILES['gambar']['name']){
        $gambar = "data:image/png;base64,". base64_encode(file_get_contents($_FILES['gambar']['tmp_name']));
        $sql.= ", gambar='$gambar'";
    }
    $sql.= " WHERE id=$id";
    $conn->query($sql);
    header("Location: admin_produk.php");
}

$produk = $conn->query("SELECT p.*,k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori=k.id");
$kategori = $conn->query("SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html>
<head>
<title>Kelola Produk - Admin</title>
<style>
*{font-family:Poppins,sans-serif;margin:0}body{display:flex;background:#FFF5F7}
.sidebar{width:250px;background:#2C3E50;color:#fff;min-height:100vh;padding:25px 0}
.sidebar a{display:block;color:#ecf0f1;padding:14px 25px;text-decoration:none;margin:5px 15px;border-radius:8px}
.sidebar a:hover,.active{background:#E91E63}
.content{flex:1;padding:30px}
.topbar{background:#fff;padding:20px;border-radius:12px;margin-bottom:20px}
.btn{background:#E91E63;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600}
.btn-edit{background:#2196F3}.btn-hapus{background:#f44336}
table{width:100%;background:#fff;border-radius:12px;padding:20px;border-collapse:collapse}
th,td{padding:12px;text-align:left;border-bottom:1px solid #eee}
th{background:#FFF5F7;color:#E91E63}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center}
.modal-content{background:#fff;padding:30px;border-radius:15px;width:500px}
input,textarea,select{width:100%;padding:10px;border:1.5px solid #F8BBD0;border-radius:8px;margin:8px 0}
</style>
<script>
function bukaModal(id,nama,desc,harga,stok,kat){
    document.getElementById('modal').style.display='flex';
    document.getElementById('eid').value=id;
    document.getElementById('enama').value=nama;
    document.getElementById('edesc').value=desc;
    document.getElementById('eharga').value=harga;
    document.getElementById('estok').value=stok;
    document.getElementById('ekat').value=kat;
}
function tutupModal(){document.getElementById('modal').style.display='none'}
</script>
</head>
<body>
<div class="sidebar">
  <h2 style="text-align:center;color:#E91E63">ADMIN</h2>
  <a href="admin.php">Dashboard</a>
  <a href="admin_produk.php" class="active">Produk</a>
  <a href="admin_kategori.php">Kategori</a>
  <a href="admin_pesanan.php">Pesanan</a>
  <a href="logout.php">Logout</a>
</div>

<div class="content">
  <div class="topbar">
    <h2>Kelola Produk</h2>
    <button class="btn" onclick="document.getElementById('modalTambah').style.display='flex'">+ Tambah Produk</button>
  </div>

  <table>
    <tr><th>Foto</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
    <?php while($p=$produk->fetch_assoc()):?>
    <tr>
      <td><img src="<?=$p['gambar']?>" width="60" style="border-radius:8px"></td>
      <td><?=$p['nama_produk']?></td>
      <td><?=$p['nama_kategori']?></td>
      <td>Rp<?=number_format($p['harga'])?></td>
      <td><?=$p['stok']?></td>
      <td>
        <button class="btn btn-edit" onclick="bukaModal('<?=$p['id']?>','<?=$p['nama_produk']?>','<?=$p['deskripsi']?>','<?=$p['harga']?>','<?=$p['stok']?>','<?=$p['id_kategori']?>')">Edit</button>
        <a href="?hapus=<?=$p['id']?>" onclick="return confirm('Hapus?')"><button class="btn btn-hapus">Hapus</button></a>
      </td>
    </tr>
    <?php endwhile;?>
  </table>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="modal" onclick="this.style.display='none'">
  <div class="modal-content" onclick="event.stopPropagation()">
    <h3>Tambah Produk</h3>
    <form method="post" enctype="multipart/form-data">
      <input name="nama" placeholder="Nama Produk" required>
      <textarea name="desc" placeholder="Deskripsi" rows="3" required></textarea>
      <input name="harga" type="number" placeholder="Harga" required>
      <input name="stok" type="number" placeholder="Stok" required>
      <select name="kategori" required>
        <option value="">Pilih Kategori</option>
        <?php $kategori->data_seek(0); while($k=$kategori->fetch_assoc()) echo "<option value='$k[id]'>$k[nama_kategori]</option>";?>
      </select>
      <input name="gambar" type="file" accept="image/*" required>
      <button name="tambah" class="btn" style="width:100%;margin-top:10px">Simpan</button>
    </form>
    <button onclick="document.getElementById('modalTambah').style.display='none'" style="margin-top:10px;width:100%">Batal</button>
  </div>
</div>

<!-- Modal Edit -->
<div id="modal" class="modal" onclick="tutupModal()">
  <div class="modal-content" onclick="event.stopPropagation()">
    <h3>Edit Produk</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" id="eid">
      <input name="nama" id="enama" placeholder="Nama Produk" required>
      <textarea name="desc" id="edesc" placeholder="Deskripsi" rows="3" required></textarea>
      <input name="harga" id="eharga" type="number" placeholder="Harga" required>
      <input name="stok" id="estok" type="number" placeholder="Stok" required>
      <select name="kategori" id="ekat" required>
        <?php $kategori->data_seek(0); while($k=$kategori->fetch_assoc()) echo "<option value='$k[id]'>$k[nama_kategori]</option>";?>
      </select>
      <label>Upload Foto Baru: <input name="gambar" type="file" accept="image/*"></label>
      <button name="edit" class="btn" style="width:100%;margin-top:10px">Update</button>
    </form>
    <button onclick="tutupModal()" style="margin-top:10px;width:100%">Batal</button>
  </div>
</div>
</body>
</html>