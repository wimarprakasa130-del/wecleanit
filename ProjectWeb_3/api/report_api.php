<?php
require_once '../koneksi.php';

// Hanya Admin yang boleh akses API ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
    exit;
}

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

// 1. Ambil Summary (Ringkasan)
if ($action == 'get_summary') {
    header('Content-Type: application/json');
    $today = date('Y-m-d');
    $month = date('Y-m');

    // Pesanan hari ini
    $q_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM orders WHERE order_date = '$today'");
    $today_orders = mysqli_fetch_assoc($q_today)['total'];

    // Pesanan bulan ini
    $q_month = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM orders WHERE order_date LIKE '$month%'");
    $month_orders = mysqli_fetch_assoc($q_month)['total'];

    // Estimasi Pendapatan Bulan Ini (dari pesanan selesai)
    $q_revenue = mysqli_query($koneksi, "SELECT SUM(price) as total FROM orders WHERE order_date LIKE '$month%' AND status = 'Selesai'");
    $revenue = mysqli_fetch_assoc($q_revenue)['total'] ?? 0;

    // Total Customer Aktif
    $q_customers = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
    $active_customers = mysqli_fetch_assoc($q_customers)['total'];

    echo json_encode([
        'status' => 'success',
        'data' => [
            'today_orders' => $today_orders,
            'month_orders' => $month_orders,
            'revenue' => $revenue,
            'active_customers' => $active_customers
        ]
    ]);
}

// 2. Laporan Lanjutan Bulanan
elseif ($action == 'get_monthly_report') {
    header('Content-Type: application/json');
    $month = bersihkan_input($_POST['month'] ?? date('Y-m'));

    $q_revenue = mysqli_query($koneksi, "SELECT SUM(price) as total FROM orders WHERE order_date LIKE '$month%' AND status = 'Selesai'");
    $revenue = mysqli_fetch_assoc($q_revenue)['total'] ?? 0;

    $q_done = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM orders WHERE order_date LIKE '$month%' AND status = 'Selesai'");
    $done = mysqli_fetch_assoc($q_done)['total'];

    $q_cancel = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM orders WHERE order_date LIKE '$month%' AND status = 'Dibatalkan'");
    $cancel = mysqli_fetch_assoc($q_cancel)['total'];

    echo json_encode([
        'status' => 'success',
        'data' => [
            'revenue' => $revenue,
            'completed' => $done,
            'canceled' => $cancel
        ]
    ]);
}

// 3. Export CSV (Harus pake GET karena didownload)
elseif ($action == 'export_csv') {
    $month = bersihkan_input($_GET['month'] ?? date('Y-m'));
    
    // Set Header untuk Download CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Laporan_WeCleanIt_' . $month . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Header Kolom CSV
    fputcsv($output, array('ID Pesanan', 'Tanggal', 'Nama Customer', 'Paket', 'Harga', 'Status'));
    
    $query = "SELECT o.id, o.order_date, u.name, o.package_name, o.price, o.status 
              FROM orders o 
              LEFT JOIN users u ON o.user_id = u.id
              WHERE o.order_date LIKE '$month%'
              ORDER BY o.order_date ASC";
              
    $result = mysqli_query($koneksi, $query);
    
    while($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}
else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
}
?>
