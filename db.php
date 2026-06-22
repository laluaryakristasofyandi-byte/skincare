<?php
session_start();
$conn = new mysqli("localhost","root","","skincare_db");
if($conn->connect_error) die("Koneksi gagal: ".$conn->connect_error);

function base(){ return "http://localhost/skincare/"; }
function cekLogin(){ if(!isset($_SESSION['user'])) header("Location: ".base()."login.php"); }
?>