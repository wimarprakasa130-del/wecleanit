<?php
require_once '../koneksi.php';

header('Content-Type: application/json');
$action = $_POST['action'] ?? '';

// API ini bisa diakses Customer (Read) dan Admin (CRUD)
$user_role = $_SESSION['role'] ?? 'guest';

// 1. READ: Ambil semua paket aktif
if ($action == 'get_packages') {
    // Kalau admin, bisa lihat semua. Kalau customer, hanya yang aktif
    $where = ($user_role == 'admin') ? "" : "WHERE is_active = 1";
    
    $query = "SELECT * FROM packages $where ORDER BY price ASC";
    $result = mysqli_query($koneksi, $query);
    
    $packages = [];
    while($row = mysqli_fetch_assoc($result)) {
        $packages[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $packages]);
}

// ================= BATAS AKSES ADMIN =================
elseif ($user_role != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Hanya admin yang bisa mengubah paket']);
    exit;
}

// 2. ADMIN CREATE: Tambah Paket Baru
elseif ($action == 'add_package') {
    $name        = bersihkan_input($_POST['name'] ?? '');
    $price       = bersihkan_input($_POST['price'] ?? 0);
    $description = bersihkan_input($_POST['description'] ?? '');

    if(empty($name) || empty($price) || empty($description)){
        echo json_encode(['status' => 'error', 'message' => 'Semua data paket wajib diisi']);
        exit;
    }

    $query = "INSERT INTO packages (name, price, description) VALUES ('$name', $price, '$description')";
    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Paket berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambah paket']);
    }
}

// 3. ADMIN UPDATE: Edit Paket
elseif ($action == 'update_package') {
    $id          = bersihkan_input($_POST['id'] ?? 0);
    $name        = bersihkan_input($_POST['name'] ?? '');
    $price       = bersihkan_input($_POST['price'] ?? 0);
    $description = bersihkan_input($_POST['description'] ?? '');
    
    $query = "UPDATE packages SET name = '$name', price = $price, description = '$description' WHERE id = $id";
    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Paket berhasil diupdate']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate paket']);
    }
}

// 4. ADMIN DELETE: Soft Delete Paket (Nonaktifkan)
elseif ($action == 'delete_package') {
    $id = bersihkan_input($_POST['id'] ?? 0);
    
    $query = "UPDATE packages SET is_active = 0 WHERE id = $id";
    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Paket dinonaktifkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menonaktifkan paket']);
    }
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
}
?>
