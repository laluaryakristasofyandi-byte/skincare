<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
$err = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $q = $stmt->get_result();
    
    if($q->num_rows > 0){
        $user = $q->fetch_assoc();
        
        if(password_verify($pass, $user['password'])){
            session_regenerate_id(); // biar aman
            $_SESSION['user'] = $user;
            
            if($user['role'] == 'admin'){
                header("Location: admin.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            $err = "Password salah! Cek lagi passwordnya";
        }
    } else {
        $err = "Email tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login - Skincare Shop</title>
<style>
*{font-family:Poppins,sans-serif;margin:0;box-sizing:border-box}
body{background:#FFF5F7;display:flex;align-items:center;justify-content:center;min-height:100vh}
.wrap{display:flex;width:900px;height:550px;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 10px 40px rgba(233,30,99,0.15)}
.left{width:50%;background:linear-gradient(135deg,#FCE4EC,#F8BBD0);display:flex;align-items:center;justify-content:center;color:#E91E63;font-size:28px;font-weight:700}
.right{width:50%;padding:50px 40px}
.logo{color:#E91E63;font-weight:700;font-size:24px;text-align:center;margin-bottom:30px}
h2{color:#333;margin-bottom:5px}
.sub{color:#999;font-size:14px;margin-bottom:25px}
input{width:100%;padding:14px;border:1.5px solid #F8BBD0;border-radius:10px;margin-bottom:15px;outline:none;font-size:14px}
input:focus{border-color:#E91E63}
.btn{background:#E91E63;color:#fff;border:none;padding:14px;width:100%;border-radius:10px;font-weight:600;font-size:15px;cursor:pointer}
.error{color:red;font-size:13px;margin-bottom:10px;text-align:center}
a{color:#E91E63;text-decoration:none;font-size:13px}
</style>
</head>
<body>
<div class="wrap">
  <div class="left">SKINCARE<br>SHOP</div>
  <div class="right">
    <div class="logo">SKINCARE SHOP</div>
    <h2>Selamat Datang Kembali 👋</h2>
    <p class="sub">Login untuk melanjutkan akun Anda</p>
    
    <?php if($err) echo "<div class='error'>$err</div>"; ?>
    
    <form method="post">
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password" required>
      <button name="login" class="btn">LOGIN</button>
    </form>
    
    <p style="text-align:center;margin-top:20px;font-size:14px">
      Belum punya akun? <a href="register.php">Daftar di sini</a>
    </p>
  </div>
</div>
</body>
</html>