<?php
require_once '../koneksi.php';

// Hanya Admin yang boleh akses API ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
    exit;
}

header('Content-Type: application/json');
$action = $_POST['action'] ?? '';

// 1. Ambil Data Semua Customer
if ($action == 'get_customers') {
    $search = bersihkan_input($_POST['search'] ?? '');
    $where = "WHERE role = 'customer'";
    
    if(!empty($search)){
        $where .= " AND (name LIKE '%$search%' OR phone LIKE '%$search%')";
    }

    $query = "SELECT u.id, u.name, u.phone, u.created_at,
              (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as total_orders
              FROM users u 
              $where 
              ORDER BY u.created_at DESC";
              
    $result = mysqli_query($koneksi, $query);
    $customers = [];
    while($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $customers]);
}
else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
}
?>
