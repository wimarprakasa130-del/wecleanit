<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: auth.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer | WeCleanIt</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkblue: '#0F5A96',
                        skyblue: '#74B4D9',
                        lightsky: '#E0F2FE',
                        neutralbg: '#F0F9FF',
                        whatsapp: '#25D366'
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        heading: ['Syne', 'sans-serif'],
                    }
                }
            }
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
            <h1 class="text-2xl font-bold font-heading tracking-tight">WeCleanIt</h1>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 mt-4">
            <button onclick="switchTab('dashboard')" id="nav-desk-dashboard" class="w-full flex items-center gap-3 px-4 py-3 bg-skyblue text-white rounded-xl font-bold transition-all outline-none">
                <i class="fa-solid fa-house w-5"></i> Beranda
            </button>
            <button onclick="switchTab('pesan')" id="nav-desk-pesan" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold transition-all outline-none">
                <i class="fa-solid fa-circle-plus w-5"></i> Pesan Layanan
            </button>
            <button onclick="switchTab('riwayat')" id="nav-desk-riwayat" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold transition-all outline-none">
                <i class="fa-solid fa-clock-rotate-left w-5"></i> Riwayat
            </button>
            <button onclick="switchTab('profil')" id="nav-desk-profil" class="w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold transition-all outline-none">
                <i class="fa-solid fa-user w-5"></i> Profil
            </button>
        </nav>

        <div class="p-4 border-t border-white/10">
            <button onclick="logout()" class="w-full flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/20 rounded-xl font-bold transition-all outline-none">
                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Keluar
            </button>
        </div>
    </aside>

    <div class="md:hidden bg-darkblue text-white p-4 sticky top-0 z-50 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-sparkles text-skyblue text-xl"></i>
            <h1 class="text-xl font-bold font-heading">WeCleanIt</h1>
        </div>
        <i class="fa-solid fa-bell text-lightsky"></i>
    </div>

    <main class="flex-1 p-4 md:p-8 w-full max-w-[100vw] overflow-x-hidden">

        <!-- ================= TAB: DASHBOARD ================= -->
        <div id="tab-dashboard" class="fade-in space-y-6 block">
            
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-darkblue mb-1">Halo, <span id="user-greeting-name">Loading...</span>! 👋</h2>
                <p class="text-gray-500 text-sm">Siap untuk kamar yang lebih bersih hari ini?</p>
            </div>

            <!-- PROMO BANNER (NEW) -->
            <div class="bg-gradient-to-r from-orange-400 to-red-500 rounded-3xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
                <div class="relative z-10 w-full">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-white text-red-500 text-xs font-bold px-2 py-1 rounded">PROMO SPESIAL</span>
                        <span class="text-sm font-bold opacity-90"><i class="fa-solid fa-clock"></i> Terbatas!</span>
                    </div>
                    <h3 class="font-bold text-xl md:text-2xl mb-1 font-heading">Diskon 20% Paket Ekstra</h3>
                    <p class="text-white/80 text-sm mb-4">Gunakan kode promo <strong class="bg-white/20 px-2 py-0.5 rounded">BERSIHKILAT20</strong> saat melakukan pembayaran. Jangan sampai kehabisan!</p>
                    <button onclick="switchTab('pesan')" class="bg-white text-red-500 px-5 py-2 rounded-full font-bold text-sm shadow-md hover:bg-neutralbg transition-all outline-none">
                        Pakai Promo Sekarang
                    </button>
                </div>
                <i class="fa-solid fa-gift text-7xl text-white/20 absolute right-6 md:right-10 bottom-1/2 transform translate-y-1/2 rotate-12"></i>
            </div>

            <!-- Pesanan Aktif -->
            <div id="active-order-container" class="hidden bg-white rounded-3xl p-6 shadow-sm border border-skyblue/30 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-skyblue"></div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Pesanan Aktif Anda</span>
                        <h3 id="active-order-package" class="font-bold text-lg text-gray-800">-</h3>
                    </div>
                    <span id="active-order-status" class="px-3 py-1 rounded-full text-xs font-bold border bg-yellow-100 text-yellow-700 border-yellow-200 flex items-center gap-1">
                        <i class="fa-regular fa-clock"></i> Menunggu
                    </span>
                </div>
                <div class="flex items-center text-sm text-gray-600 mb-6 gap-2">
                    <i class="fa-regular fa-calendar text-darkblue"></i>
                    Jadwal: <strong id="active-order-date" class="text-gray-800">-</strong>
                </div>
                <div class="flex gap-3">
                    <button onclick="switchTab('riwayat')" class="px-5 py-2 bg-lightsky text-darkblue rounded-xl font-bold text-sm hover:bg-skyblue hover:text-white transition-colors outline-none">
                        Lihat Detail
                    </button>
                    <button id="btn-cancel-active" onclick="cancelOrder('')" class="px-5 py-2 bg-white border border-red-200 text-red-500 rounded-xl font-bold text-sm hover:bg-red-50 transition-colors outline-none">
                        Batalkan
                    </button>
                </div>
            </div>

            <div class="bg-gradient-to-r from-darkblue to-skyblue rounded-3xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
                <div class="relative z-10 w-full">
                    <h3 class="font-bold text-lg mb-1">Kamar sudah mulai kotor?</h3>
                    <p class="text-lightsky text-sm mb-4">Pesan ulang layanan kebersihan sekarang.</p>
                    <button onclick="switchTab('pesan')" class="bg-white text-darkblue px-5 py-2 rounded-full font-bold text-sm shadow-md hover:bg-neutralbg transition-all outline-none">
                        Pesan Layanan
                    </button>
                </div>
                <i class="fa-solid fa-sparkles text-7xl text-white/10 absolute right-4 bottom-4 transform rotate-12 hidden sm:block"></i>
            </div>

            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800">Pesanan Terakhir</h3>
                    <button onclick="switchTab('riwayat')" class="text-sm font-bold text-skyblue hover:text-darkblue flex items-center gap-1 outline-none">
                        Lihat Semua <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
                <div id="mini-history-container" class="space-y-3"></div>
            </div>
        </div>

        <!-- ================= TAB: PESAN LAYANAN (5-STEP WIZARD) ================= -->
        <div id="tab-pesan" class="hidden fade-in pb-10 max-w-2xl">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <button id="btn-back-step" onclick="prevStep()" class="text-gray-500 hover:text-darkblue flex items-center text-sm font-bold outline-none" style="visibility: hidden;">
                        <i class="fa-solid fa-chevron-left mr-1"></i> Kembali
                    </button>
                    <span id="step-indicator-text" class="text-sm font-bold text-skyblue">Langkah 1 dari 5</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="progress-bar-fill" class="bg-skyblue h-2.5 rounded-full transition-all duration-300" style="width: 20%"></div>
                </div>
            </div>

            <!-- Step 1: Pilih Paket -->
            <div id="step-1" class="space-y-4">
                <h2 class="text-2xl font-bold text-darkblue mb-2">Pilih Paket Kebersihan</h2>
                <div id="packages-container" class="space-y-3"></div>
                <button id="btn-next-1" disabled onclick="nextStep()" class="w-full mt-6 py-3 bg-darkblue disabled:bg-gray-300 disabled:opacity-70 disabled:cursor-not-allowed text-white rounded-xl font-bold transition-colors outline-none">
                    Lanjut Pilih Jadwal
                </button>
            </div>

            <!-- Step 2: Pilih Jadwal -->
            <div id="step-2" class="space-y-4 hidden">
                <h2 class="text-2xl font-bold text-darkblue mb-2">Tentukan Jadwal</h2>
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs p-3 rounded-lg flex items-start gap-2 mb-4">
                    <i class="fa-solid fa-circle-exclamation shrink-0 mt-0.5"></i>
                    <p>Pemesanan minimal <strong>H-1</strong>. Tidak melayani pemesanan untuk hari yang sama.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2"><i class="fa-solid fa-calendar text-skyblue"></i> Tanggal Kedatangan</label>
                    <input type="date" id="booking-date" onchange="validateStep2()" class="w-full p-3 border border-gray-200 rounded-xl outline-none focus:border-skyblue">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2"><i class="fa-regular fa-clock text-skyblue"></i> Slot Waktu</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="selectTime('08:00 - 10:00', this)" class="time-btn py-3 rounded-xl border border-gray-200 bg-white text-gray-600 font-bold text-sm transition-all outline-none">08:00 - 10:00</button>
                        <button onclick="selectTime('10:00 - 12:00', this)" class="time-btn py-3 rounded-xl border border-gray-200 bg-white text-gray-600 font-bold text-sm transition-all outline-none">10:00 - 12:00</button>
                        <button onclick="selectTime('13:00 - 15:00', this)" class="time-btn py-3 rounded-xl border border-gray-200 bg-white text-gray-600 font-bold text-sm transition-all outline-none">13:00 - 15:00</button>
                        <button onclick="selectTime('15:00 - 17:00', this)" class="time-btn py-3 rounded-xl border border-gray-200 bg-white text-gray-600 font-bold text-sm transition-all outline-none">15:00 - 17:00</button>
                    </div>
                </div>
                <button id="btn-next-2" disabled onclick="nextStep()" class="w-full mt-8 py-3 bg-darkblue disabled:bg-gray-300 text-white rounded-xl font-bold transition-colors outline-none">
                    Lanjut Konfirmasi Alamat
                </button>
            </div>

            <!-- Step 3: Alamat -->
            <div id="step-3" class="space-y-4 hidden">
                <h2 class="text-2xl font-bold text-darkblue mb-2">Detail Alamat</h2>
                <textarea id="booking-address" rows="4" oninput="validateStep3()" placeholder="Contoh: Jl. Majapahit No. 10 (Kosan Bu Ratna, Kamar 04)" class="w-full p-3 border border-gray-200 rounded-xl outline-none focus:border-skyblue resize-none"></textarea>
                <button id="btn-next-3" disabled onclick="nextStep()" class="w-full mt-6 py-3 bg-darkblue disabled:bg-gray-300 text-white rounded-xl font-bold transition-colors outline-none">
                    Lanjut Pilih Pembayaran
                </button>
            </div>

            <!-- Step 4: Pembayaran (NEW) -->
            <div id="step-4" class="space-y-4 hidden">
                <h2 class="text-2xl font-bold text-darkblue mb-2">Metode Pembayaran</h2>
                <p class="text-gray-500 text-sm mb-4">Pilih metode pembayaran yang paling nyaman untuk Anda.</p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="selectPayment('QRIS', this)" class="pay-btn p-4 rounded-xl border border-gray-200 bg-white flex flex-col items-center justify-center gap-2 transition-all outline-none">
                        <i class="fa-solid fa-qrcode text-2xl text-gray-700"></i>
                        <span class="font-bold text-sm">QRIS</span>
                    </button>
                    <button onclick="selectPayment('ShopeePay', this)" class="pay-btn p-4 rounded-xl border border-gray-200 bg-white flex flex-col items-center justify-center gap-2 transition-all outline-none">
                        <i class="fa-solid fa-wallet text-2xl text-orange-500"></i>
                        <span class="font-bold text-sm">ShopeePay</span>
                    </button>
                    <button onclick="selectPayment('GoPay', this)" class="pay-btn p-4 rounded-xl border border-gray-200 bg-white flex flex-col items-center justify-center gap-2 transition-all outline-none">
                        <i class="fa-solid fa-wallet text-2xl text-blue-500"></i>
                        <span class="font-bold text-sm">GoPay</span>
                    </button>
                    <button onclick="selectPayment('DANA', this)" class="pay-btn p-4 rounded-xl border border-gray-200 bg-white flex flex-col items-center justify-center gap-2 transition-all outline-none">
                        <i class="fa-solid fa-wallet text-2xl text-blue-400"></i>
                        <span class="font-bold text-sm">DANA</span>
                    </button>
                    <button onclick="selectPayment('Transfer Mandiri', this)" class="pay-btn p-4 rounded-xl border border-gray-200 bg-white flex flex-col items-center justify-center gap-2 transition-all outline-none col-span-2">
                        <i class="fa-solid fa-building-columns text-2xl text-darkblue"></i>
                        <span class="font-bold text-sm">Transfer Bank Mandiri</span>
                    </button>
                </div>

                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mt-4">
                    <p class="text-xs text-blue-800"><i class="fa-solid fa-info-circle"></i> Ini adalah simulasi. Pembayaran akan langsung dianggap berhasil saat dikonfirmasi.</p>
                </div>

                <button id="btn-next-4" disabled onclick="nextStep()" class="w-full mt-6 py-3 bg-darkblue disabled:bg-gray-300 text-white rounded-xl font-bold transition-colors outline-none">
                    Lihat Ringkasan Pesanan
                </button>
            </div>

            <!-- Step 5: Ringkasan -->
            <div id="step-5" class="space-y-6 hidden">
                <h2 class="text-2xl font-bold text-darkblue mb-2">Ringkasan Pesanan</h2>
                <div class="bg-white p-5 rounded-2xl border border-lightsky shadow-sm space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <div><p class="text-sm text-gray-500 font-bold mb-1">Paket Pilihan</p><p id="summary-package" class="font-bold text-darkblue text-lg">-</p></div>
                        <span id="summary-price" class="text-lg font-bold text-gray-800">-</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <div><p class="text-sm text-gray-500 font-bold mb-1">Jadwal</p><p id="summary-datetime" class="font-bold text-gray-800">-</p></div>
                        <i class="fa-regular fa-calendar-check text-2xl text-skyblue"></i>
                    </div>
                    <div class="pb-4 border-b border-gray-100">
                        <p class="text-sm text-gray-500 font-bold mb-1">Alamat Tujuan</p>
                        <p id="summary-address" class="text-sm text-gray-800">-</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 font-bold">Metode Pembayaran</p>
                        <span id="summary-payment" class="px-3 py-1 bg-green-100 text-green-700 font-bold text-xs rounded-lg">-</span>
                    </div>
                </div>

                <button onclick="submitBooking()" id="btn-submit-booking" class="w-full py-4 bg-skyblue hover:bg-blue-400 text-white rounded-xl font-bold text-lg shadow-lg flex justify-center items-center gap-2 outline-none">
                    <i class="fa-solid fa-circle-check"></i> Konfirmasi & Bayar
                </button>
            </div>
        </div>

        <!-- ================= TAB: RIWAYAT ================= -->
        <div id="tab-riwayat" class="hidden fade-in space-y-4 max-w-4xl">
            <h2 class="text-2xl font-bold text-darkblue mb-6">Riwayat Pesanan</h2>
            <div id="full-history-container" class="space-y-4"></div>
        </div>

        <!-- ================= TAB: PROFIL ================= -->
        <div id="tab-profil" class="hidden fade-in max-w-lg mx-auto py-10">
            <h2 class="text-2xl font-bold text-darkblue mb-6 text-center">Profil Saya</h2>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <img id="profile-image" src="" class="w-24 h-24 rounded-full object-cover border-4 border-lightsky">
                    <label for="input-photo" class="absolute bottom-0 right-0 w-8 h-8 bg-skyblue text-white rounded-full flex items-center justify-center cursor-pointer shadow-md">
                        <i class="fa-solid fa-camera text-sm"></i>
                    </label>
                    <input type="file" id="input-photo" accept="image/png, image/jpeg, image/jpg" class="hidden" onchange="uploadPhoto(this)">
                </div>
                <form id="form-profile" class="space-y-4">
                    <div><label class="block text-sm font-bold text-gray-700 mb-1">Nama</label><input type="text" id="prof-name" class="w-full p-3 border rounded-xl"></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-1">WA</label><input type="tel" id="prof-phone" class="w-full p-3 border rounded-xl"></div>
                    <button type="submit" id="btn-save-profile" class="w-full py-3 bg-darkblue text-white rounded-xl font-bold">Simpan Perubahan</button>
                </form>
            </div>
        </div>

    </main>

    <!-- Mobile Nav -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-50">
        <div class="flex justify-around items-center p-2">
            <button onclick="switchTab('dashboard')" id="nav-mob-dashboard" class="nav-mob flex flex-col items-center p-2 text-darkblue"><i class="fa-solid fa-house"></i><span class="text-[10px]">Beranda</span></button>
            <button onclick="switchTab('pesan')" id="nav-mob-pesan" class="nav-mob flex flex-col items-center p-2 text-gray-400"><i class="fa-solid fa-circle-plus"></i><span class="text-[10px]">Pesan</span></button>
            <button onclick="switchTab('riwayat')" id="nav-mob-riwayat" class="nav-mob flex flex-col items-center p-2 text-gray-400"><i class="fa-solid fa-clock-rotate-left"></i><span class="text-[10px]">Riwayat</span></button>
            <button onclick="switchTab('profil')" id="nav-mob-profil" class="nav-mob flex flex-col items-center p-2 text-gray-400"><i class="fa-solid fa-user"></i><span class="text-[10px]">Profil</span></button>
        </div>
    </div>

    <!-- Modal Simulasi Bayar -->
    <div id="modal-payment" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center">
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center transform scale-95 transition-transform duration-300" id="modal-payment-content">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-darkblue mb-2">Pembayaran Berhasil!</h3>
            <p class="text-gray-500 text-sm mb-6">Pesanan Anda telah diteruskan ke tim kami.</p>
            <button onclick="finishPayment()" class="w-full py-3 bg-skyblue text-white font-bold rounded-xl">Lihat Riwayat</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadProfile(); loadPackages(); loadMyOrders();
            const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('booking-date').min = tomorrow.toISOString().split('T')[0];
        });

        function formatRp(num) { return 'Rp ' + parseInt(num).toLocaleString('id-ID'); }

        function switchTab(tabName) {
            ['dashboard', 'pesan', 'riwayat', 'profil'].forEach(t => document.getElementById('tab-'+t).classList.add('hidden'));
            document.getElementById('tab-'+tabName).classList.remove('hidden');
            ['dashboard', 'pesan', 'riwayat', 'profil'].forEach(t => document.getElementById('nav-desk-'+t).className = "w-full flex items-center gap-3 px-4 py-3 text-lightsky hover:bg-white/10 rounded-xl font-semibold");
            document.getElementById('nav-desk-'+tabName).className = "w-full flex items-center gap-3 px-4 py-3 bg-skyblue text-white rounded-xl font-bold";
            document.querySelectorAll('.nav-mob').forEach(n => { n.classList.remove('text-darkblue'); n.classList.add('text-gray-400'); });
            const mob = document.getElementById('nav-mob-'+tabName);
            if(mob) { mob.classList.remove('text-gray-400'); mob.classList.add('text-darkblue'); }
            window.scrollTo(0,0);
        }

        function loadProfile() {
            fetch('api/profile_api.php', { method: 'POST', body: new URLSearchParams({action: 'get_profile'}) })
            .then(r=>r.json()).then(res=>{
                if(res.status==='success'){
                    document.getElementById('user-greeting-name').innerText = res.data.name.split(' ')[0];
                    document.getElementById('prof-name').value = res.data.name;
                    document.getElementById('prof-phone').value = res.data.phone;
                    document.getElementById('profile-image').src = res.data.profile_picture_url;
                }
            });
        }

        document.getElementById('form-profile').onsubmit = e => {
            e.preventDefault();
            const fd = new FormData(); fd.append('action', 'update_profile');
            fd.append('name', document.getElementById('prof-name').value);
            fd.append('phone', document.getElementById('prof-phone').value);
            fetch('api/profile_api.php', { method: 'POST', body: fd }).then(r=>r.json()).then(res=>{ alert(res.message); loadProfile(); });
        };

        function loadMyOrders() {
            fetch('api/order_api.php', { method: 'POST', body: new URLSearchParams({action: 'get_my_orders'}) })
            .then(r=>r.json()).then(res=>{ if(res.status==='success') renderOrders(res.data); });
        }

        function renderOrders(orders) {
            const history = document.getElementById('full-history-container');
            const mini = document.getElementById('mini-history-container');
            const activeDiv = document.getElementById('active-order-container');
            history.innerHTML=''; mini.innerHTML='';
            let hasActive = false;

            orders.forEach((o, i) => {
                let bc = o.status==='Selesai'?'bg-green-100 text-green-700':o.status==='Dibatalkan'?'bg-red-100 text-red-700':'bg-yellow-100 text-yellow-700';
                history.insertAdjacentHTML('beforeend', `
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-400">${o.id} • ${o.payment_method}</span>
                        <span class="px-2 py-1 rounded text-xs font-bold ${bc}">${o.status}</span>
                    </div>
                    <h3 class="font-bold text-gray-800">${o.package_name} <span class="text-xs text-gray-400">${formatRp(o.price)}</span></h3>
                    <p class="text-sm text-gray-500">${o.order_date}, ${o.order_time}</p>
                </div>`);

                if(!hasActive && ['Menunggu','Dikonfirmasi','Sedang Dikerjakan'].includes(o.status)) {
                    hasActive = true; activeDiv.classList.remove('hidden');
                    document.getElementById('active-order-package').innerText = o.package_name;
                    document.getElementById('active-order-status').className = `px-3 py-1 rounded-full text-xs font-bold border flex items-center gap-1 ${bc}`;
                    document.getElementById('active-order-status').innerHTML = `<i class="fa-regular fa-clock"></i> ${o.status}`;
                    document.getElementById('active-order-date').innerText = o.order_date + ' ' + o.order_time;
                }
                if(i < 3) mini.insertAdjacentHTML('beforeend', `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                        <div><p class="font-bold text-gray-800 text-sm">${o.package_name}</p><p class="text-xs text-gray-500">${o.order_date}</p></div>
                        <span class="px-2 py-1 rounded text-xs font-bold ${bc}">${o.status}</span>
                    </div>`);
            });
            if(!hasActive) activeDiv.classList.add('hidden');
        }

        // --- WIZARD ---
        let currentStep = 1;
        let bData = { package: '', price: 0, date: '', time: '', address: '', payment: '' };

        function loadPackages() {
            fetch('api/package_api.php', { method: 'POST', body: new URLSearchParams({action:'get_packages'}) }).then(r=>r.json()).then(res=>{
                const c = document.getElementById('packages-container'); c.innerHTML='';
                res.data.forEach(p => c.insertAdjacentHTML('beforeend', `
                    <div onclick="selPkg('${p.name}', ${p.price}, this)" class="pkg-card p-5 rounded-2xl border-2 border-gray-100 bg-white cursor-pointer hover:border-lightsky">
                        <h3 class="font-bold">${p.name} <span class="float-right text-darkblue">${formatRp(p.price)}</span></h3>
                    </div>`));
            });
        }

        function renderStep() {
            for(let i=1; i<=5; i++) document.getElementById('step-'+i).classList.add('hidden');
            document.getElementById('step-'+currentStep).classList.remove('hidden');
            document.getElementById('step-indicator-text').innerText = 'Langkah '+currentStep+' dari 5';
            document.getElementById('progress-bar-fill').style.width = (currentStep*20)+'%';
            document.getElementById('btn-back-step').style.visibility = currentStep>1 ? 'visible' : 'hidden';

            if(currentStep === 5) {
                document.getElementById('summary-package').innerText = bData.package;
                document.getElementById('summary-price').innerText = formatRp(bData.price);
                document.getElementById('summary-datetime').innerText = bData.date + ' | ' + bData.time;
                document.getElementById('summary-address').innerText = bData.address;
                document.getElementById('summary-payment').innerText = bData.payment;
            }
        }

        function nextStep() { if(currentStep<5){ currentStep++; renderStep(); window.scrollTo(0,0); } }
        function prevStep() { if(currentStep>1){ currentStep--; renderStep(); window.scrollTo(0,0); } }

        function selPkg(name, price, el) {
            bData.package = name; bData.price = price;
            document.querySelectorAll('.pkg-card').forEach(c => c.className="pkg-card p-5 rounded-2xl border-2 border-gray-100 bg-white cursor-pointer hover:border-lightsky");
            el.className="pkg-card p-5 rounded-2xl border-2 border-skyblue bg-neutralbg cursor-pointer";
            document.getElementById('btn-next-1').disabled = false;
        }

        function selectTime(t, el) {
            bData.time = t;
            document.querySelectorAll('.time-btn').forEach(b => b.className="time-btn py-3 rounded-xl border border-gray-200 bg-white text-gray-600 font-bold text-sm");
            el.className="time-btn py-3 rounded-xl border-skyblue bg-skyblue text-white font-bold text-sm";
            validateStep2();
        }

        function validateStep2() { bData.date=document.getElementById('booking-date').value; document.getElementById('btn-next-2').disabled=!(bData.date&&bData.time); }
        function validateStep3() { bData.address=document.getElementById('booking-address').value; document.getElementById('btn-next-3').disabled=(bData.address.trim().length===0); }

        function selectPayment(m, el) {
            bData.payment = m;
            document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('border-skyblue', 'bg-neutralbg'));
            el.classList.add('border-skyblue', 'bg-neutralbg');
            document.getElementById('btn-next-4').disabled = false;
        }

        function submitBooking() {
            const btn = document.getElementById('btn-submit-booking');
            btn.innerHTML = 'Memproses...'; btn.disabled = true;

            const fd = new URLSearchParams();
            fd.append('action', 'create_order');
            fd.append('package', bData.package); fd.append('price', bData.price);
            fd.append('date', bData.date); fd.append('time', bData.time);
            fd.append('address', bData.address); fd.append('payment_method', bData.payment);

            fetch('api/order_api.php', { method: 'POST', body: fd }).then(r=>r.json()).then(res=>{
                if(res.status==='success'){
                    document.getElementById('modal-payment').classList.remove('hidden');
                    document.getElementById('modal-payment-content').classList.remove('scale-95');
                    document.getElementById('modal-payment-content').classList.add('scale-100');
                } else alert(res.message);
            }).finally(() => { btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Konfirmasi & Bayar'; btn.disabled = false; });
        }

        function finishPayment() {
            document.getElementById('modal-payment').classList.add('hidden');
            loadMyOrders(); switchTab('riwayat');
            // Reset
            currentStep=1; bData={ package:'', price:0, date:'', time:'', address:'', payment:'' };
            document.getElementById('booking-date').value=''; document.getElementById('booking-address').value='';
            ['1','2','3','4'].forEach(i=>document.getElementById('btn-next-'+i).disabled=true);
            loadPackages(); renderStep();
        }

        function logout() { if(confirm('Keluar?')) { fetch('api/auth_api.php', {method:'POST', body:new URLSearchParams({action:'logout'})}).then(()=>window.location.href='auth.php'); } }
    </script>
</body>
</html>
