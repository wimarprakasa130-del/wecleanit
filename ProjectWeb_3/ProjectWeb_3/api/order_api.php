<?php
require_once '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

header('Content-Type: application/json');
$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Generate ID Pesanan (Format: ORD-XXXX)
function generateOrderID($koneksi) {
    $result = mysqli_query($koneksi, "SELECT id FROM orders ORDER BY id DESC LIMIT 1");
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $last_id = $row['id'];
        $num = (int)substr($last_id, 4);
        $new_num = $num + 1;
    } else {
        $new_num = 1;
    }
    return "ORD-" . str_pad($new_num, 4, "0", STR_PAD_LEFT);
}

// 1. CUSTOMER: Buat Pesanan Baru
if ($action == 'create_order' && $user_role == 'customer') {
    $package_name = bersihkan_input($_POST['package'] ?? '');
    $price        = bersihkan_input($_POST['price'] ?? 0);
    $date         = bersihkan_input($_POST['date'] ?? '');
    $time         = bersihkan_input($_POST['time'] ?? '');
    $address      = bersihkan_input($_POST['address'] ?? '');
    $payment      = bersihkan_input($_POST['payment_method'] ?? 'Tunai');

    if(empty($package_name) || empty($date) || empty($time) || empty($address)){
        echo json_encode(['status' => 'error', 'message' => 'Data pesanan tidak lengkap']);
        exit;
    }

    $order_id = generateOrderID($koneksi);
    
    $query = "INSERT INTO orders (id, user_id, package_name, price, address_detail, order_date, order_time, payment_method, payment_status, status) 
              VALUES ('$order_id', $user_id, '$package_name', $price, '$address', '$date', '$time', '$payment', 'Sudah Dibayar', 'Menunggu')";

    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dibuat!', 'order_id' => $order_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal membuat pesanan: ' . mysqli_error($koneksi)]);
    }
}

// 2. CUSTOMER: Lihat Riwayat Sendiri
elseif ($action == 'get_my_orders' && $user_role == 'customer') {
    $query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    $result = mysqli_query($koneksi, $query);
    $orders = [];
    while($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $orders]);
}

// 3. CUSTOMER: Batalkan Pesanan Sendiri
elseif ($action == 'cancel_order' && $user_role == 'customer') {
    $order_id = bersihkan_input($_POST['order_id'] ?? '');
    
    // Pastikan pesanan itu milik user ini dan status masih 'Menunggu'
    $query = "UPDATE orders SET status = 'Dibatalkan', cancel_reason = 'Dibatalkan oleh Customer' 
              WHERE id = '$order_id' AND user_id = $user_id AND status = 'Menunggu'";
              
    if(mysqli_query($koneksi, $query) && mysqli_affected_rows($koneksi) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dibatalkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tidak dapat membatalkan pesanan ini (Mungkin sudah dikonfirmasi)']);
    }
}

// 4. ADMIN: Lihat Semua Pesanan
elseif ($action == 'get_all_orders' && $user_role == 'admin') {
    $status_filter = bersihkan_input($_POST['status'] ?? '');
    
    $where = "";
    if(!empty($status_filter) && $status_filter != 'Semua Status') {
        $where = "WHERE o.status = '$status_filter'";
    }

    $query = "SELECT o.*, u.name as customer_name, u.phone as customer_phone, c.name as cleaner_name 
              FROM orders o 
              LEFT JOIN users u ON o.user_id = u.id 
              LEFT JOIN cleaners c ON o.cleaner_id = c.id
              $where 
              ORDER BY o.created_at DESC";
              
    $result = mysqli_query($koneksi, $query);
    $orders = [];
    while($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $orders]);
}

// 5. ADMIN: Konfirmasi Pesanan
elseif ($action == 'confirm_order' && $user_role == 'admin') {
    $order_id = bersihkan_input($_POST['order_id'] ?? '');
    
    $query = "UPDATE orders SET status = 'Dikonfirmasi' WHERE id = '$order_id' AND status = 'Menunggu'";
    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesanan dikonfirmasi']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal konfirmasi']);
    }
}

// 6. ADMIN: Tugaskan Cleaner
elseif ($action == 'assign_cleaner' && $user_role == 'admin') {
    $order_id = bersihkan_input($_POST['order_id'] ?? '');
    $cleaner_id = bersihkan_input($_POST['cleaner_id'] ?? '');
    
    if(empty($cleaner_id)){
        echo json_encode(['status' => 'error', 'message' => 'Pilih cleaner terlebih dahulu']);
        exit;
    }

    $query = "UPDATE orders SET cleaner_id = $cleaner_id, status = 'Sedang Dikerjakan' WHERE id = '$order_id'";
    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Cleaner berhasil ditugaskan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menugaskan cleaner']);
    }
}

// 7. ADMIN: Update Status (Selesai/Batal)
elseif ($action == 'update_status' && $user_role == 'admin') {
    $order_id = bersihkan_input($_POST['order_id'] ?? '');
    $new_status = bersihkan_input($_POST['status'] ?? '');
    $reason = bersihkan_input($_POST['reason'] ?? '');
    
    $query = "UPDATE orders SET status = '$new_status', cancel_reason = '$reason' WHERE id = '$order_id'";
    if(mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diubah menjadi ' . $new_status]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal merubah status']);
    }
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid atau tidak memiliki akses']);
}
?>
