<?php
require_once '../koneksi.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action == 'register') {
    $name     = bersihkan_input($_POST['name'] ?? '');
    $phone    = bersihkan_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if(empty($name) || empty($phone) || empty($password)){
        echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi']);
        exit;
    }

    // Cek apakah nomor sudah terdaftar
    $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE phone = '$phone'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Nomor WhatsApp sudah terdaftar']);
        exit;
    }

    // Hash password & simpan ke database
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (name, phone, password_hash, role) VALUES ('$name', '$phone', '$hash', 'customer')";
    
    if(mysqli_query($koneksi, $query)){
        echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil! Silakan login.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mendaftar: ' . mysqli_error($koneksi)]);
    }
}

elseif ($action == 'login') {
    $phone    = bersihkan_input($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if(empty($phone) || empty($password)){
        echo json_encode(['status' => 'error', 'message' => 'Nomor WhatsApp dan Password wajib diisi']);
        exit;
    }

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE phone = '$phone'");
    
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        
        // Verifikasi password
        if (password_verify($password, $user['password_hash'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];
            
            // Redirect URL berdasarkan role
            $redirect_url = ($user['role'] == 'admin') ? 'dashboardAdmin.php' : 'dashboardCustomer.php';

            echo json_encode([
                'status' => 'success', 
                'message' => 'Login berhasil', 
                'role' => $user['role'],
                'redirect' => $redirect_url
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nomor WhatsApp tidak terdaftar']);
    }
}

elseif ($action == 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logout berhasil']);
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
}
?>
