<?php
require_once '../koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

if ($action == 'get_profile') {
    $query = mysqli_query($koneksi, "SELECT id, name, phone, profile_picture FROM users WHERE id = $user_id");
    $user = mysqli_fetch_assoc($query);
    
    // Set default image jika belum ada foto
    if(empty($user['profile_picture'])){
        $user['profile_picture_url'] = 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=74B4D9&color=fff&rounded=true';
    } else {
        $user['profile_picture_url'] = 'uploads/profiles/' . $user['profile_picture'];
    }

    echo json_encode(['status' => 'success', 'data' => $user]);
}

elseif ($action == 'update_profile') {
    $name  = bersihkan_input($_POST['name'] ?? '');
    $phone = bersihkan_input($_POST['phone'] ?? '');

    if(empty($name) || empty($phone)){
        echo json_encode(['status' => 'error', 'message' => 'Nama dan Nomor WhatsApp wajib diisi']);
        exit;
    }

    // Cek apakah nomor WA dipakai user lain
    $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE phone = '$phone' AND id != $user_id");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Nomor WhatsApp sudah dipakai akun lain']);
        exit;
    }

    $query = "UPDATE users SET name = '$name', phone = '$phone' WHERE id = $user_id";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['name'] = $name; // Update session
        echo json_encode(['status' => 'success', 'message' => 'Profil berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil']);
    }
}

elseif ($action == 'upload_photo') {
    // 1. Cek apakah ada file yang diupload
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] != 0) {
        echo json_encode(['status' => 'error', 'message' => 'Pilih foto terlebih dahulu']);
        exit;
    }
    
    // 2. Validasi tipe file (hanya gambar)
    $tipe_boleh = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($_FILES['photo']['type'], $tipe_boleh)) {
        echo json_encode(['status' => 'error', 'message' => 'Format harus JPG atau PNG']);
        exit;
    }
    
    // 3. Validasi ukuran (max 2MB = 2 * 1024 * 1024 byte)
    if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'Ukuran max 2MB']);
        exit;
    }
    
    // 4. Buat nama file unik
    $ekstensi  = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $nama_file = "profile_" . $user_id . "_" . time() . "." . $ekstensi;
    $tujuan    = "../uploads/profiles/" . $nama_file;
    
    // 5. Pindahkan file
    if(move_uploaded_file($_FILES['photo']['tmp_name'], $tujuan)) {
        // Ambil foto lama untuk dihapus
        $query_old = mysqli_query($koneksi, "SELECT profile_picture FROM users WHERE id = $user_id");
        $old_data = mysqli_fetch_assoc($query_old);
        if(!empty($old_data['profile_picture']) && file_exists("../uploads/profiles/" . $old_data['profile_picture'])){
            unlink("../uploads/profiles/" . $old_data['profile_picture']); // Hapus file lama
        }

        // 6. Simpan ke database
        mysqli_query($koneksi, "UPDATE users SET profile_picture = '$nama_file' WHERE id = $user_id");
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Foto profil berhasil diperbarui',
            'url' => 'uploads/profiles/' . $nama_file
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload file']);
    }
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
}
?>
