<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
$err = $sukses = "";

if(isset($_POST['daftar'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // cek email udah ada belum
    $cek = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($cek->num_rows > 0){
        $err = "Email sudah terdaftar!";
    } else {
        $conn->query("INSERT INTO users (nama,email,password,role) VALUES ('$nama','$email','$pass','user')");
        $sukses = "Daftar berhasil! Silakan login";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Daftar - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;box-sizing:border-box}
body{background:#FFF5F7;display:flex;align-items:center;justify-content:center;min-height:100vh}
.wrap{display:flex;width:900px;height:550px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 10px 40px rgba(233,30,99,0.15)}
.left{width:50%;background:linear-gradient(135deg,#FCE4EC,#F8BBD0);display:flex;align-items:center;justify-content:center;color:#E91E63;font-size:28px;font-weight:700}
.right{width:50%;padding:40px}
.logo{color:#E91E63;font-weight:700;font-size:24px;text-align:center;margin-bottom:25px}
h2{color:#333;margin-bottom:5px}
.sub{color:#999;font-size:14px;margin-bottom:20px}
input{width:100%;padding:14px;border:1.5px solid #F8BBD0;border-radius:10px;margin-bottom:12px;outline:none;font-size:14px}
.btn{background:#E91E63;color:#fff;border:none;padding:14px;width:100%;border-radius:10px;font-weight:600;font-size:15px;cursor:pointer}
.error{color:red;font-size:13px;margin-bottom:10px;text-align:center}
.sukses{color:green;font-size:13px;margin-bottom:10px;text-align:center}
a{color:#E91E63;text-decoration:none}
</style>
</head>
<body>
<div class="wrap">
  <div class="left">DAFTAR<br>SEKARANG</div>
  <div class="right">
    <div class="logo">SKINCARE SHOP</div>
    <h2>Buat Akun Baru</h2>
    <p class="sub">Gratis, hanya butuh 1 menit</p>
    
    <?php if($err) echo "<div class='error'>$err</div>"; ?>
    <?php if($sukses) echo "<div class='sukses'>$sukses</div>"; ?>
    
    <form method="post">
      <input name="nama" placeholder="Nama Lengkap" required>
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password minimal 6 karakter" minlength="6" required>
      <button name="daftar" class="btn">DAFTAR</button>
    </form>
    
    <p style="text-align:center;margin-top:20px;font-size:14px">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>
  </div>
</div>
</body>
</html>