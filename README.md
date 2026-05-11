# WeCleanIt

## Deskripsi
adalah platform aplikasi web mobile-first penyedia layanan jasa kebersihan profesional yang dirancang khusus untuk area Mataram dan sekitarnya. Target utamanya adalah mahasiswa, karyawan, dan rumah tangga yang membutuhkan layanan kebersihan (sapu, pel, sikat kamar mandi, dll) secara praktis, transparan, dan terpercaya.

Sistem ini mengubah cara tradisional (pesan manual lewat chat yang rentan miskomunikasi) menjadi sistem pemesanan mandiri (sistem booking otomatis) yang rapi, baik untuk pelanggan maupun pengelola bisnis (Admin).
## Alamat
[http://localhost]

## Menu Utama
```
- Customer (Pelanggan)
    - Landing Page
    - Authentication Page (Login, Register, OTP)
    - Dashboard (Pesanan Aktif & Jalan Pintas)
    - Booking Page (Wizard Pemesanan 4 Langkah)
    - Order History & Review Page
    - Profile & Multi-Address Management Page
- Admin (Pengelola)
    - Admin Login Page
    - Overview Dashboard (Statistik Keuangan & Operasional)
    - Order Management Page (Konfirmasi, Tugaskan Staf, Selesai, Batal)
    - Schedule/Slot Availability Page
    - Staff/Cleaner Management Page
    - Customer Data Page
    - Package & Price Management Page
    - Reports Page (Export CSV)
```

## Teknologi
HTML, PHP, CSS, Javascript, Tailwind, MySQL

# Previous Description

Antartika is a random project we decided to make, it is an app rating platform where you could rate app put there. Antartika is made using CodeIgniter version 4 and thus followed object-oriented MVC (Model-View-Controller) architecture.

**Note:** this project is just a test project, so don't try to deploy it on the web.

**Another Note:** please don't look into the code of this project or you will be haunted by eternal nightmare.

**More Note:** nothing.

## Requirement
To use Antartika you must've had all this installed and configured:
- PHP
- Composer
- MySQL/MariaDB

## Quick Start
```
$ git clone https://github.com/NotMyButOurTeam/Antartika
$ cd Antartika
$ composer install
$ cp env .env
$ php spark serve
```

## (Not Optional) Database Configuration
To use the database you must first create an account named 'antartika' on your localhost. This account does not have any password. After that, create a database named 'Antartika' with the previously created account. Next import the content of ```sql/mydb.sql``` into the database.

And... Done!
