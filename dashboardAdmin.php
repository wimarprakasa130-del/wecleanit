<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | WeCleanIt</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { darkblue: '#0F5A96', skyblue: '#74B4D9', lightsky: '#E0F2FE', neutralbg: '#F0F9FF', whatsapp: '#25D366' }, fontFamily: { sans: ['DM Sans', 'sans-serif'], heading: ['Syne', 'sans-serif'] } } }
        }
    </script>
    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { height: 6px; width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #74B4D9; border-radius: 10px; }
    </style>
</head>
<body class="font-sans bg-neutralbg text-gray-800 antialiased flex flex-col md:flex-row min-h-screen pb-20 md:pb-0">

    <aside class="hidden md:flex flex-col w-64 bg-darkblue text-white min-h-screen sticky top-0 shadow-xl z-50 transition-all">
        <div class="p-6 flex items-center gap-3 border-b border-white/10">
            <i class="fa-solid fa-sparkles text-skyblue text-2xl"></i>
            <h1 class="text-2xl font-bold font-heading">Admin</h1>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 mt-4">
            <button onclick="switchAdminTab('overview')" id="nav-desk-overview" class="w-full flex items-center gap-3 px-4 py-3 bg-skyblue text-white rounded-xl font-bold"><i class="fa-solid fa-chart-pie w-5"></i> Ringkasan</button>
            <button onclick="switchAdminTab('pesanan')" id="nav-desk-pesanan" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold"><i class="fa-solid fa-clipboard-list w-5"></i> Pesanan</button>
            <button onclick="switchAdminTab('petugas')" id="nav-desk-petugas" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold"><i class="fa-solid fa-user-shield w-5"></i> Data Petugas</button>
            <button onclick="switchAdminTab('customer')" id="nav-desk-customer" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold"><i class="fa-solid fa-users w-5"></i> Customer</button>
            <button onclick="switchAdminTab('paket')" id="nav-desk-paket" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold"><i class="fa-solid fa-tags w-5"></i> Paket</button>
            <button onclick="switchAdminTab('laporan')" id="nav-desk-laporan" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold"><i class="fa-solid fa-file-invoice w-5"></i> Laporan</button>
        </nav>

        <div class="p-4 border-t border-white/10">
            <button onclick="logout()" class="w-full flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/20 rounded-xl font-bold"><i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Keluar</button>
        </div>
    </aside>

    <main class="flex-1 p-4 md:p-8 w-full overflow-x-hidden">

        <div class="flex justify-between items-end mb-8">
            <div><h2 class="text-2xl md:text-3xl font-bold text-darkblue mb-1">Selamat Datang, Admin!</h2><p class="text-gray-500 text-sm">Berikut adalah ringkasan operasional WeCleanIt hari ini.</p></div>
        </div>

        <!-- === TAB: OVERVIEW === -->
        <div id="tab-overview" class="fade-in block space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-lightsky shadow-sm"><div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 mb-3 flex items-center justify-center"><i class="fa-solid fa-calendar-day"></i></div><p class="text-xs text-gray-500 font-bold mb-1">Pesanan Hari Ini</p><h3 id="stat-today" class="text-2xl font-bold text-darkblue">0</h3></div>
                <div class="bg-white p-5 rounded-2xl border border-lightsky shadow-sm"><div class="w-10 h-10 rounded-full bg-green-50 text-green-500 mb-3 flex items-center justify-center"><i class="fa-solid fa-check-double"></i></div><p class="text-xs text-gray-500 font-bold mb-1">Bulan Ini</p><h3 id="stat-month" class="text-2xl font-bold text-darkblue">0</h3></div>
                <div class="bg-white p-5 rounded-2xl border border-lightsky shadow-sm"><div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 mb-3 flex items-center justify-center"><i class="fa-solid fa-wallet"></i></div><p class="text-xs text-gray-500 font-bold mb-1">Pendapatan</p><h3 id="stat-revenue" class="text-xl md:text-2xl font-bold text-darkblue">Rp 0</h3></div>
                <div class="bg-white p-5 rounded-2xl border border-lightsky shadow-sm"><div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 mb-3 flex items-center justify-center"><i class="fa-solid fa-users"></i></div><p class="text-xs text-gray-500 font-bold mb-1">Customer</p><h3 id="stat-customers" class="text-2xl font-bold text-darkblue">0</h3></div>
            </div>
            
            <div class="bg-white rounded-3xl border border-lightsky shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-neutralbg/50"><h3 class="font-bold text-darkblue">Pesanan Menunggu Konfirmasi</h3></div>
                <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">ID</th><th class="p-4">Customer</th><th class="p-4">Paket & Jadwal</th><th class="p-4 text-right">Aksi</th></tr></thead><tbody id="quick-order-list" class="divide-y divide-gray-100"></tbody></table></div>
            </div>
        </div>

        <!-- === TAB: PESANAN === -->
        <div id="tab-pesanan" class="hidden fade-in space-y-6">
            <div class="flex justify-between"><h3 class="text-xl font-bold text-darkblue">Manajemen Pesanan</h3><select id="filter-order-status" onchange="loadOrders()" class="px-4 py-2 rounded-xl border outline-none bg-white"><option>Semua Status</option><option>Menunggu</option><option>Dikonfirmasi</option><option>Sedang Dikerjakan</option><option>Selesai</option><option>Dibatalkan</option></select></div>
            <div class="bg-white rounded-2xl border shadow-sm overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">ID Pesanan</th><th class="p-4">Customer</th><th class="p-4">Paket & Pembayaran</th><th class="p-4">Status & Cleaner</th><th class="p-4 text-center">Aksi</th></tr></thead><tbody id="full-order-list" class="divide-y divide-gray-100"></tbody></table></div>
        </div>

        <!-- === TAB: PETUGAS (NEW) === -->
        <div id="tab-petugas" class="hidden fade-in space-y-6">
            <div class="flex justify-between items-center"><h3 class="text-xl font-bold text-darkblue">Data Petugas (Cleaner)</h3><button onclick="openModalPetugas()" class="px-4 py-2 bg-skyblue text-white font-bold rounded-xl text-sm shadow-sm hover:bg-blue-400"><i class="fa-solid fa-plus"></i> Tambah Petugas</button></div>
            <div class="bg-white rounded-2xl border shadow-sm overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Nama Petugas</th><th class="p-4">No WA</th><th class="p-4">Status</th><th class="p-4 text-right">Aksi</th></tr></thead><tbody id="petugas-list" class="divide-y divide-gray-100"></tbody></table></div>
        </div>

        <!-- === TAB: CUSTOMER === -->
        <div id="tab-customer" class="hidden fade-in space-y-6">
            <h3 class="text-xl font-bold text-darkblue">Data Customer</h3>
            <div class="bg-white rounded-2xl border shadow-sm overflow-x-auto"><table class="w-full text-left text-sm"><thead class="bg-gray-50 text-gray-500"><tr><th class="p-4">Nama</th><th class="p-4">WhatsApp</th><th class="p-4 text-center">Total Pesanan</th><th class="p-4">Bergabung</th></tr></thead><tbody id="customer-list" class="divide-y divide-gray-100"></tbody></table></div>
        </div>

        <!-- === TAB: PAKET === -->
        <div id="tab-paket" class="hidden fade-in space-y-6">
            <div class="flex justify-between items-center"><h3 class="text-xl font-bold text-darkblue">Manajemen Paket</h3><button onclick="openModalPaket()" class="px-4 py-2 bg-skyblue text-white font-bold rounded-xl text-sm shadow-sm hover:bg-blue-400"><i class="fa-solid fa-plus"></i> Tambah Paket</button></div>
            <div id="package-list" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
        </div>

        <!-- === TAB: LAPORAN === -->
        <div id="tab-laporan" class="hidden fade-in space-y-6">
            <div class="flex justify-between items-center"><h3 class="text-xl font-bold text-darkblue">Laporan Keuangan</h3><button onclick="exportCSV()" class="px-4 py-2 bg-green-600 text-white font-bold rounded-xl text-sm"><i class="fa-solid fa-file-csv"></i> Export CSV</button></div>
            <div class="bg-white rounded-2xl border shadow-sm p-6 text-center"><div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fa-solid fa-chart-line text-2xl"></i></div><h4 class="font-bold text-lg mb-2">Pendapatan Bulan Ini</h4><p id="report-revenue" class="text-3xl font-bold text-darkblue mb-6">Rp 0</p></div>
        </div>

    </main>

    <!-- MODAL PETUGAS -->
    <div id="modal-petugas" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm"><h3 class="font-bold text-xl text-darkblue mb-4">Form Petugas</h3>
            <form id="form-petugas" class="space-y-4"><input type="hidden" id="petugas-id"><input type="hidden" id="petugas-action" value="add_cleaner">
                <div><label class="block text-sm font-bold mb-1">Nama Petugas</label><input type="text" id="petugas-name" required class="w-full p-3 border rounded-xl"></div>
                <div><label class="block text-sm font-bold mb-1">No WA</label><input type="tel" id="petugas-phone" required class="w-full p-3 border rounded-xl"></div>
                <div><label class="block text-sm font-bold mb-1">Status</label><select id="petugas-status" class="w-full p-3 border rounded-xl"><option>Tersedia</option><option>Bertugas</option><option>Off</option></select></div>
                <div class="flex gap-3"><button type="button" onclick="closeModalPetugas()" class="flex-1 py-3 bg-gray-100 rounded-xl font-bold">Batal</button><button type="submit" class="flex-1 py-3 bg-skyblue text-white rounded-xl font-bold">Simpan</button></div>
            </form>
        </div>
    </div>

    <!-- MODAL ASSIGN CLEANER -->
    <div id="modal-assign" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-sm"><h3 class="font-bold text-xl text-darkblue mb-4">Tugaskan Cleaner</h3>
            <div class="space-y-4">
                <input type="hidden" id="assign-order-id">
                <div><label class="block text-sm font-bold mb-1">Pilih Cleaner Tersedia</label>
                    <select id="assign-cleaner-id" class="w-full p-3 border rounded-xl bg-white"></select>
                </div>
                <div class="flex gap-3"><button onclick="closeModalAssign()" class="flex-1 py-3 bg-gray-100 rounded-xl font-bold">Batal</button><button onclick="submitAssignCleaner()" class="flex-1 py-3 bg-skyblue text-white rounded-xl font-bold">Tugaskan</button></div>
            </div>
        </div>
    </div>

    <!-- MODAL PAKET -->
    <div id="modal-paket" class="fixed inset-0 bg-black/50 z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md"><h3 class="font-bold text-xl text-darkblue mb-4">Form Paket</h3>
            <form id="form-paket" class="space-y-4"><input type="hidden" id="paket-id"><input type="hidden" id="paket-action" value="add_package">
                <div><label class="block text-sm font-bold mb-1">Nama</label><input type="text" id="paket-name" required class="w-full p-3 border rounded-xl"></div>
                <div><label class="block text-sm font-bold mb-1">Harga</label><input type="number" id="paket-price" required class="w-full p-3 border rounded-xl"></div>
                <div><label class="block text-sm font-bold mb-1">Deskripsi</label><textarea id="paket-desc" required class="w-full p-3 border rounded-xl"></textarea></div>
                <div class="flex gap-3"><button type="button" onclick="document.getElementById('modal-paket').classList.add('hidden')" class="flex-1 py-3 bg-gray-100 rounded-xl font-bold">Batal</button><button type="submit" class="flex-1 py-3 bg-skyblue text-white rounded-xl font-bold">Simpan</button></div>
            </form>
        </div>
    </div>

    <script>
        const formatRp = num => 'Rp ' + parseInt(num).toLocaleString('id-ID');
        let globalCleaners = [];

        document.addEventListener('DOMContentLoaded', () => { loadSummary(); loadOrders(); loadCustomers(); loadPackages(); loadCleaners(); loadReport(); });

        function switchAdminTab(t) {
            ['overview', 'pesanan', 'petugas', 'customer', 'paket', 'laporan'].forEach(x => {
                document.getElementById('tab-'+x).classList.add('hidden');
                document.getElementById('nav-desk-'+x).className = "w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold";
            });
            document.getElementById('tab-'+t).classList.remove('hidden');
            document.getElementById('nav-desk-'+t).className = "w-full flex items-center gap-3 px-4 py-3 bg-skyblue text-white rounded-xl font-bold";
        }

        // --- DASHBOARD DATA ---
        function loadSummary() {
            fetch('api/report_api.php', { method: 'POST', body: new URLSearchParams({action:'get_summary'}) }).then(r=>r.json()).then(res=>{
                if(res.status==='success'){
                    document.getElementById('stat-today').innerText = res.data.today_orders;
                    document.getElementById('stat-month').innerText = res.data.month_orders;
                    document.getElementById('stat-revenue').innerText = formatRp(res.data.revenue);
                    document.getElementById('stat-customers').innerText = res.data.active_customers;
                }
            });
        }
        function loadReport() { fetch('api/report_api.php', { method: 'POST', body: new URLSearchParams({action:'get_monthly_report'}) }).then(r=>r.json()).then(res=> document.getElementById('report-revenue').innerText=formatRp(res.data.revenue)); }

        // --- PETUGAS (CLEANERS) ---
        function loadCleaners() {
            fetch('api/cleaner_api.php', { method: 'POST', body: new URLSearchParams({action:'get_cleaners'}) }).then(r=>r.json()).then(res=>{
                if(res.status==='success'){
                    globalCleaners = res.data;
                    const list = document.getElementById('petugas-list'); list.innerHTML='';
                    res.data.forEach(c => {
                        let badge = c.status==='Tersedia'?'bg-green-100 text-green-700':c.status==='Bertugas'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700';
                        list.insertAdjacentHTML('beforeend', `<tr class="hover:bg-gray-50"><td class="p-4 font-bold text-darkblue">${c.name}</td><td class="p-4">${c.phone}</td><td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold ${badge}">${c.status}</span></td><td class="p-4 text-right"><button onclick="openModalPetugas(${c.id},'${c.name}','${c.phone}','${c.status}')" class="px-3 py-1 bg-gray-100 rounded text-sm font-bold">Edit</button> <button onclick="deletePetugas(${c.id})" class="px-3 py-1 bg-red-50 text-red-500 rounded text-sm font-bold">Hapus</button></td></tr>`);
                    });
                }
            });
        }
        function openModalPetugas(id='', name='', phone='', status='Tersedia') {
            document.getElementById('modal-petugas').classList.remove('hidden');
            document.getElementById('petugas-id').value = id; document.getElementById('petugas-name').value = name;
            document.getElementById('petugas-phone').value = phone; document.getElementById('petugas-status').value = status;
            document.getElementById('petugas-action').value = id ? 'update_cleaner' : 'add_cleaner';
        }
        function closeModalPetugas() { document.getElementById('modal-petugas').classList.add('hidden'); }
        document.getElementById('form-petugas').onsubmit = e => {
            e.preventDefault(); const fd = new URLSearchParams(new FormData(e.target));
            fd.append('action', document.getElementById('petugas-action').value); fd.append('id', document.getElementById('petugas-id').value);
            fd.append('name', document.getElementById('petugas-name').value); fd.append('phone', document.getElementById('petugas-phone').value); fd.append('status', document.getElementById('petugas-status').value);
            fetch('api/cleaner_api.php', { method: 'POST', body: fd }).then(r=>r.json()).then(res=>{ alert(res.message); if(res.status==='success'){ closeModalPetugas(); loadCleaners(); }});
        };
        function deletePetugas(id) {
            if(confirm('Hapus petugas ini?')) { fetch('api/cleaner_api.php', { method: 'POST', body: new URLSearchParams({action:'delete_cleaner', id:id}) }).then(r=>r.json()).then(res=>{ alert(res.message); loadCleaners(); }); }
        }

        // --- ASSIGN CLEANER MODAL ---
        function openModalAssign(orderId) {
            document.getElementById('assign-order-id').value = orderId;
            const select = document.getElementById('assign-cleaner-id'); select.innerHTML = '<option value="">-- Pilih Petugas Tersedia --</option>';
            globalCleaners.filter(c => c.status === 'Tersedia').forEach(c => select.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`));
            document.getElementById('modal-assign').classList.remove('hidden');
        }
        function closeModalAssign() { document.getElementById('modal-assign').classList.add('hidden'); }
        function submitAssignCleaner() {
            const oid = document.getElementById('assign-order-id').value; const cid = document.getElementById('assign-cleaner-id').value;
            if(!cid) return alert('Pilih petugas!');
            fetch('api/order_api.php', { method: 'POST', body: new URLSearchParams({action:'assign_cleaner', order_id:oid, cleaner_id:cid}) }).then(r=>r.json()).then(res=>{ alert(res.message); closeModalAssign(); loadOrders(); loadCleaners(); });
        }

        // --- PESANAN ---
        function loadOrders() {
            const st = document.getElementById('filter-order-status').value;
            fetch('api/order_api.php', { method: 'POST', body: new URLSearchParams({action:'get_all_orders', status:st}) }).then(r=>r.json()).then(res=>{
                const full = document.getElementById('full-order-list'); const quick = document.getElementById('quick-order-list');
                full.innerHTML=''; quick.innerHTML=''; let wC = 0;
                res.data.forEach(o => {
                    let bc = o.status==='Menunggu'?'bg-yellow-100 text-yellow-700':o.status==='Selesai'?'bg-green-100 text-green-700':'bg-blue-100 text-blue-700';
                    let act = o.status==='Menunggu' ? `<button onclick="confirmOrder('${o.id}')" class="px-2 py-1 bg-green-50 text-green-600 rounded font-bold text-xs"><i class="fa-solid fa-check"></i> Acc</button> <button onclick="cancelOrderAdmin('${o.id}')" class="px-2 py-1 bg-red-50 text-red-600 rounded font-bold text-xs"><i class="fa-solid fa-xmark"></i> Batal</button>` : 
                             (o.status==='Dikonfirmasi' ? `<button onclick="openModalAssign('${o.id}')" class="px-2 py-1 bg-skyblue text-white rounded font-bold text-xs">Tugaskan</button>` : 
                             (o.status==='Sedang Dikerjakan' ? `<button onclick="updateStatus('${o.id}','Selesai')" class="px-2 py-1 bg-green-50 text-green-600 rounded font-bold text-xs">Selesai</button>` : ''));
                    
                    full.insertAdjacentHTML('beforeend', `<tr class="hover:bg-gray-50 border-b border-gray-100"><td class="p-4 font-bold text-gray-700">${o.id}</td><td class="p-4"><p class="font-bold">${o.customer_name}</p></td><td class="p-4"><p class="font-bold">${o.package_name}</p><p class="text-xs text-gray-500">${o.payment_method}</p></td><td class="p-4"><span class="px-2 py-1 rounded text-xs font-bold ${bc}">${o.status}</span><p class="text-xs mt-1 font-bold text-darkblue">${o.cleaner_name?'Cleaner: '+o.cleaner_name:''}</p></td><td class="p-4 text-center">${act}</td></tr>`);
                    if(o.status==='Menunggu') { wC++; quick.insertAdjacentHTML('beforeend', `<tr><td class="p-4">${o.id}</td><td class="p-4">${o.customer_name}</td><td class="p-4">${o.package_name}</td><td class="p-4 text-right"><button onclick="confirmOrder('${o.id}')" class="px-3 py-1 bg-skyblue text-white rounded font-bold text-xs">Konfirmasi</button></td></tr>`); }
                });
                if(wC===0) quick.innerHTML = '<tr><td colspan="4" class="p-4 text-center text-gray-500">Aman, tidak ada yang menunggu.</td></tr>';
            });
        }
        function confirmOrder(id) { if(confirm('Acc pesanan?')) fetch('api/order_api.php', { method: 'POST', body: new URLSearchParams({action:'confirm_order', order_id:id}) }).then(()=>loadOrders()); }
        function updateStatus(id, st) { if(confirm('Update status jadi '+st+'?')) fetch('api/order_api.php', { method: 'POST', body: new URLSearchParams({action:'update_status', order_id:id, status:st}) }).then(()=>loadOrders()); }
        function cancelOrderAdmin(id) { const r = prompt("Alasan:"); if(r) fetch('api/order_api.php', { method: 'POST', body: new URLSearchParams({action:'update_status', order_id:id, status:'Dibatalkan', reason:r}) }).then(()=>loadOrders()); }

        // --- CUSTOMER, PAKET, LOGOUT ---
        function loadCustomers() { fetch('api/customer_api.php', { method: 'POST', body: new URLSearchParams({action:'get_customers'}) }).then(r=>r.json()).then(res=>{ const lst=document.getElementById('customer-list'); lst.innerHTML=''; res.data.forEach(c=>lst.insertAdjacentHTML('beforeend', `<tr><td class="p-4 font-bold text-darkblue">${c.name}</td><td class="p-4">${c.phone}</td><td class="p-4 text-center font-bold">${c.total_orders}</td><td class="p-4">${c.created_at.split(' ')[0]}</td></tr>`)); }); }
        function loadPackages() { fetch('api/package_api.php', { method: 'POST', body: new URLSearchParams({action:'get_packages'}) }).then(r=>r.json()).then(res=>{ const lst=document.getElementById('package-list'); lst.innerHTML=''; res.data.forEach(p=>lst.insertAdjacentHTML('beforeend', `<div class="bg-white p-6 rounded-2xl border shadow-sm relative"><button onclick="document.getElementById('modal-paket').classList.remove('hidden'); document.getElementById('paket-id').value=${p.id}; document.getElementById('paket-name').value='${p.name}'; document.getElementById('paket-price').value=${p.price}; document.getElementById('paket-desc').value='${p.description}'; document.getElementById('paket-action').value='update_package';" class="absolute top-4 right-4 bg-gray-100 px-3 py-1 rounded text-xs font-bold">Edit</button><h4 class="font-bold text-lg mb-1">${p.name}</h4><p class="text-2xl font-bold text-darkblue mb-2">${formatRp(p.price)}</p><p class="text-sm text-gray-500">${p.description}</p></div>`)); }); }
        document.getElementById('form-paket').onsubmit = e => { e.preventDefault(); const fd = new URLSearchParams(new FormData(e.target)); fd.append('action', document.getElementById('paket-action').value); fd.append('id', document.getElementById('paket-id').value); fd.append('name', document.getElementById('paket-name').value); fd.append('price', document.getElementById('paket-price').value); fd.append('description', document.getElementById('paket-desc').value); fetch('api/package_api.php', { method:'POST', body:fd }).then(()=>{ document.getElementById('modal-paket').classList.add('hidden'); loadPackages(); }); };
        function logout() { if(confirm('Keluar?')) fetch('api/auth_api.php', { method:'POST', body:new URLSearchParams({action:'logout'}) }).then(()=>window.location.href='auth.php'); }
        function exportCSV() { window.location.href = 'api/report_api.php?action=export_csv'; }
    </script>
</body>
</html>
