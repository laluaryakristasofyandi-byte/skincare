<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if($conn->connect_error) die("Koneksi gagal");
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') header("Location: login.php");

function uploadGambar($file){
    if(!isset($file) || $file['error'] != 0){
        return [null, "Error upload: ".$file['error']];
    }
    if($file['size'] > 2000000){ // max 2MB
        return [null, "File kegedean, max 2MB"];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, ['jpg','jpeg','png','webp'])){
        return [null, "Format harus jpg/png/webp"];
    }
    $imgData = file_get_contents($file['tmp_name']);
    $base64 = 'data:image/'.$ext.';base64,'.base64_encode($imgData);
    return [$base64, null];
}

$msg = '';
if(isset($_POST['simpan'])){
    $id = $_POST['id'];
    $nama = $conn->real_escape_string($_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $kategori = (int)$_POST['id_kategori'];
    
    list($gambar, $err) = uploadGambar($_FILES['gambar_file']);
    
    if($err) $msg = $err;
    
    if($gambar == null && $id != ''){
        $gambar = $conn->query("SELECT gambar FROM produk WHERE id=$id")->fetch_assoc()['gambar'];
    }
    
    if(!$err){
        if($id == ''){
            $conn->query("INSERT INTO produk(nama_produk,harga,stok,id_kategori,gambar) VALUES('$nama',$harga,$stok,$kategori,'$gambar')");
        } else {
            $conn->query("UPDATE produk SET nama_produk='$nama',harga=$harga,stok=$stok,id_kategori=$kategori,gambar='$gambar' WHERE id=$id");
        }
        header("Location: produk.php");
        exit;
    }
}

if(isset($_GET['hapus'])){
    $conn->query("DELETE FROM produk WHERE id=".$_GET['hapus']);
    header("Location: produk.php");
}

$edit = null;
if(isset($_GET['edit'])){
    $edit = $conn->query("SELECT * FROM produk WHERE id=".$_GET['edit'])->fetch_assoc();
}

$produk = $conn->query("SELECT p.*,k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori=k.id ORDER BY p.id DESC");
$kategori = $conn->query("SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html>
<head>
<title>Data Produk</title>
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
input, select { padding:10px; border:1px solid #ddd; border-radius:5px; width:100%; margin:8px 0; }
input[type=file] { padding:8px; border:1px dashed #ff4081; background:#fff0f5; }
.alert { padding:10px; background:#ffebee; color:#c62828; border-radius:5px; margin-bottom:10px; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th { background:#f5f5f5; padding:12px; text-align:left; }
td { padding:12px; border-bottom:1px solid #eee; }
img { width:60px; height:60px; object-fit:cover; border-radius:8px; border:2px solid #eee; }
</style>
</head>
<body>
<div class="sidebar">
    <h2>SKINCARE SHOP</h2>
    <a href="admin.php">Dashboard</a>
    <a href="produk.php" class="active">Produk</a>
    <a href="kategori.php">Kategori</a>
    <a href="pelanggan.php">Pelanggan</a>
    <a href="pesanan.php">Pesanan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h1>Data Produk</h1>
    
    <?php if($msg): ?><div class="alert"><?=$msg?></div><?php endif; ?>
    
    <div class="box">
        <h3><?= $edit ? 'Edit Produk' : 'Tambah Produk' ?></h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
            <input type="text" name="nama_produk" placeholder="Nama Produk" value="<?= $edit['nama_produk'] ?? '' ?>" required>
            <input type="number" name="harga" placeholder="Harga" value="<?= $edit['harga'] ?? '' ?>" required>
            <input type="number" name="stok" placeholder="Stok" value="<?= $edit['stok'] ?? '' ?>" required>
            <select name="id_kategori" required>
                <option value="">Pilih Kategori</option>
                <?php while($k=$kategori->fetch_assoc()): ?>
                <option value="<?=$k['id']?>" <?=($edit['id_kategori']??0)==$k['id']?'selected':''?>><?=$k['nama_kategori']?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Foto Produk Max 2MB JPG/PNG:</label>
            <input type="file" name="gambar_file" accept="image/*" <?= $edit ? '' : 'required' ?>>
            
            <?php if($edit && !empty($edit['gambar'])): ?>
                <label>Foto Saat Ini:</label>
                <img src="<?=$edit['gambar']?>">
                <small style="color:#999; display:block;">Kosongkan jika tidak ganti foto</small>
            <?php endif; ?>
            
            <button type="submit" name="simpan" class="btn btn-pink" style="margin-top:15px;">Simpan</button>
            <?php if($edit): ?><a href="produk.php" class="btn">Batal</a><?php endif; ?>
        </form>
    </div>

    <div class="box">
        <table>
            <tr>
                <th>ID</th><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Terjual</th><th>Aksi</th>
            </tr>
            <?php while($p=$produk->fetch_assoc()): ?>
            <tr>
                <td><?=$p['id']?></td>
                <td><?php if(!empty($p['gambar']) && strlen($p['gambar'])>20) echo "<img src='".$p['gambar']."'>"; else echo "<span style='color:red'>No Img</span>"; ?></td>
                <td><?=$p['nama_produk']?></td>
                <td><?=$p['nama_kategori']?></td>
                <td>Rp<?=number_format($p['harga'])?></td>
                <td><?=$p['stok']?></td>
                <td><?=$p['terjual']?></td>
                <td>
                    <a href="?edit=<?=$p['id']?>" class="btn btn-blue">Edit</a>
                    <a href="?hapus=<?=$p['id']?>" class="btn btn-red" onclick="return confirm('Hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>