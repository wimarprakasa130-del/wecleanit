<?php
session_start();
// Jika sudah login, lempar ke dashboard sesuai rolenya
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') header("Location: dashboardAdmin.php");
    else header("Location: dashboardCustomer.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar | WeCleanIt</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">
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
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        .toast-enter { animation: slideInRight 0.3s ease-out forwards; }
        .toast-exit { animation: fadeOut 0.3s ease-out forwards; }
    </style>
</head>
<body class="font-sans bg-neutralbg text-gray-800 antialiased min-h-screen flex items-center justify-center p-4">

    <div id="toast-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-3"></div>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden border border-lightsky relative">
        <div class="bg-darkblue p-6 text-center text-white relative">
            <div class="absolute inset-0 bg-gradient-to-b from-darkblue to-skyblue opacity-90"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-16 h-16 bg-white text-skyblue rounded-2xl flex items-center justify-center mb-3 shadow-lg">
                    <i class="fa-solid fa-shield-halved text-3xl"></i>
                </div>
                <h1 class="font-heading text-2xl font-bold tracking-tight">WeCleanIt</h1>
                <p id="header-desc" class="text-lightsky text-sm mt-1 transition-all">Selamat datang kembali!</p>
            </div>
        </div>

        <div class="p-8">
            <!-- VIEW LOGIN -->
            <div id="view-login" class="block transition-opacity duration-300">
                <form id="form-login" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-darkblue mb-1">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-mobile-screen text-lg"></i>
                            </div>
                            <input type="tel" id="login-phone" required placeholder="08123456789" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-skyblue focus:ring-2 focus:ring-lightsky outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-darkblue mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input type="password" id="login-password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-skyblue focus:ring-2 focus:ring-lightsky outline-none transition-all">
                        </div>
                    </div>
                    <button type="submit" id="btn-login" class="w-full py-3 bg-skyblue hover:bg-blue-400 text-white rounded-xl font-bold transition-colors shadow-md shadow-skyblue/30 flex justify-center items-center gap-2 mt-4 outline-none">
                        Masuk <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Belum punya akun? 
                        <button type="button" onclick="switchView('register')" class="text-darkblue font-bold hover:underline outline-none">
                            Daftar Sekarang
                        </button>
                    </p>
                </form>
            </div>

            <!-- VIEW REGISTER -->
            <div id="view-register" class="hidden transition-opacity duration-300">
                <form id="form-register" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-darkblue mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <input type="text" id="reg-name" required placeholder="Budi Santoso" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-skyblue focus:ring-2 focus:ring-lightsky outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-darkblue mb-1">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-mobile-screen"></i>
                            </div>
                            <input type="tel" id="reg-phone" required placeholder="08123456789" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-skyblue focus:ring-2 focus:ring-lightsky outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-darkblue mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input type="password" id="reg-password" required placeholder="Buat password" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-skyblue focus:ring-2 focus:ring-lightsky outline-none transition-all">
                        </div>
                    </div>
                    <button type="submit" id="btn-register" class="w-full py-3 mt-4 bg-skyblue hover:bg-blue-400 text-white rounded-xl font-bold transition-colors shadow-md shadow-skyblue/30 outline-none">
                        Daftar Akun Baru
                    </button>
                    <p class="text-center text-sm text-gray-600 mt-4">
                        Sudah punya akun? 
                        <button type="button" onclick="switchView('login')" class="text-darkblue font-bold hover:underline outline-none">
                            Masuk di sini
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let colorClasses = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            let icon = type === 'success' ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-exclamation"></i>';

            toast.className = `toast-enter flex items-center gap-3 px-4 py-3 border-l-4 rounded shadow-md ${colorClasses}`;
            toast.innerHTML = `${icon} <span class="font-bold text-sm">${message}</span>`;
            
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('toast-enter'); toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        function switchView(viewName) {
            document.getElementById('view-login').classList.add('hidden');
            document.getElementById('view-register').classList.add('hidden');
            document.getElementById('view-' + viewName).classList.remove('hidden');

            const headerDesc = document.getElementById('header-desc');
            if(viewName === 'login') headerDesc.textContent = 'Selamat datang kembali!';
            else if(viewName === 'register') headerDesc.textContent = 'Buat akun baru Anda';
        }

        // --- REGISTRASI ---
        document.getElementById('form-register').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-register');
            btn.innerHTML = 'Memproses... <i class="fa-solid fa-spinner fa-spin"></i>'; btn.disabled = true;

            const fd = new FormData();
            fd.append('action', 'register');
            fd.append('name', document.getElementById('reg-name').value);
            fd.append('phone', document.getElementById('reg-phone').value);
            fd.append('password', document.getElementById('reg-password').value);

            fetch('api/auth_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    showToast(data.message, 'success');
                    // Langsung switch ke login dan isi formnya
                    document.getElementById('login-phone').value = document.getElementById('reg-phone').value;
                    document.getElementById('login-password').value = '';
                    switchView('login');
                    // Reset form register
                    this.reset();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'))
            .finally(() => { btn.innerHTML = 'Daftar Akun Baru'; btn.disabled = false; });
        });

        // --- LOGIN ---
        // document.getElementById('form-login').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     const btn = document.getElementById('btn-login');
        //     btn.innerHTML = 'Memeriksa... <i class="fa-solid fa-spinner fa-spin"></i>'; btn.disabled = true;

        //     const fd = new FormData();
        //     fd.append('action', 'login');
        //     fd.append('phone', document.getElementById('login-phone').value);
        //     fd.append('password', document.getElementById('login-password').value);

        //     fetch('api/auth_api.php', { method: 'POST', body: fd })
        //     .then(res => res.json())
        //     .then(data => {
        //         if(data.status === 'success') {
        //             showToast('Login berhasil! Mengalihkan...', 'success');
        //             setTimeout(() => window.location.href = data.redirect, 1000);
        //         } else {
        //             showToast(data.message, 'error');
        //         }
        //     })
        //     .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'))
        //     .finally(() => { btn.innerHTML = 'Masuk <i class="fa-solid fa-arrow-right"></i>'; btn.disabled = false; });
        // });
        document.getElementById('form-login').addEventListener('submit', function(e){
            e.preventDefault();
            const btn = document.getElementById('btn-login');
            btn.innerHTML = 'Memeriksa... <i class"fa-solid fa-spinner fa-spin"></i>'; btn.disabled = true;

            const fd = new FormData();
            fd.append('action', 'login');
            fd.append('phone', document.getElementById('login-phone').value);
            fd.append('password', document.getElementById('login-password').value);

            fetch('api/auth_api.php',{method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success'){
                    showToast('login berhasil! Mengalihkan...','success');
                    setTimeout(() => window.location.href = data.redirect, 1000);
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'))
            .finally(() => {btn.innerHTML = 'Masuk <i class="fa-solid fa-arrow-right"></i>'; btn.disabled = false;})
        })
    </script>
</body>
</html>