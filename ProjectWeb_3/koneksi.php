<?php
// Mulai sesi (Session) di sini agar otomatis aktif di semua halaman yang memanggil koneksi.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Atur zona waktu ke WITA (Mataram) sesuai lokasi operasional WeCleanIt
date_default_timezone_set('Asia/Makassar'); 

// Konfigurasi Database
$host     = "localhost";      // Server database (biasanya localhost)
$username = "root";           // Username database (default XAMPP adalah root)
$password = "";               // Password database (default XAMPP kosong)
$database = "wecleanit_db";   // Nama database yang kita buat di langkah sebelumnya

// Membuat koneksi menggunakan MySQLi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Mengecek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    // Jika gagal, hentikan program dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Opsional: Atur charset ke utf8mb4 agar mendukung karakter khusus (seperti emoji di komentar ulasan)
mysqli_set_charset($koneksi, "utf8mb4");

// Function bantuan untuk membersihkan input (mencegah SQL Injection dan XSS sederhana)
function bersihkan_input($data) {
    global $koneksi;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($koneksi, $data);
}
?>