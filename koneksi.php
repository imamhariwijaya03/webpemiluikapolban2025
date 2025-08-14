<?php
$host = "localhost";      // Nama host database
$user = "root";           // Username MySQL
$pass = "";               // Password MySQL
$db   = "ika_polban";     // Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
