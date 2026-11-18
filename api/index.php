<?php

/**
 * Pintu masuk aplikasi Laravel saat dijalankan di Vercel.
 *
 * Vercel hanya bisa mengeksekusi berkas PHP yang berada di dalam folder
 * `api/` sebagai serverless function. Berkas ini bertugas menyiapkan hal-hal
 * yang biasanya diurus oleh web server (Apache/Nginx) di lingkungan lokal,
 * lalu menyerahkan seluruh permintaan ke bootstrap Laravel.
 */

// ---------------------------------------------------------------------------
// 1. Penyesuaian direktori
// ---------------------------------------------------------------------------
// Vercel mengeksekusi function dari direktori sementara (read-only),
// sehingga folder `storage` dan `bootstrap/cache` harus dapat ditulis.
// Kita arahkan keduanya ke /tmp yang memang writable di Vercel.
$_ENV['APP_STORAGE'] = '/tmp/storage';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['SESSION_DRIVER'] = $_ENV['SESSION_DRIVER'] ?? 'cookie';

$storagePath = '/tmp/storage';

// Buat struktur direktori yang dibutuhkan Laravel bila belum ada.
$directories = [
    $storagePath,
    "{$storagePath}/app",
    "{$storagePath}/app/public",
    "{$storagePath}/framework",
    "{$storagePath}/framework/cache",
    "{$storagePath}/framework/cache/data",
    "{$storagePath}/framework/sessions",
    "{$storagePath}/framework/testing",
    "{$storagePath}/framework/views",
    "{$storagePath}/logs",
];

foreach ($directories as $directory) {
    if (! is_dir($directory)) {
        @mkdir($directory, 0777, true);
    }
}

// ---------------------------------------------------------------------------
// 2. Perlakukan sebagai request dari web server
// ---------------------------------------------------------------------------
// Laravel menentukan URL dasar dari variabel server. Script name aplikasi
// adalah /index.php, bukan /api/index.php, agar route seperti http://domain/buku
// tetap terbentuk dengan benar (tanpa prefiks /api).
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/../public/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Tangkap output yang dihasilkan agar tidak tercampur dengan header.
ob_start();

// ---------------------------------------------------------------------------
// 3. Jalankan aplikasi
// ---------------------------------------------------------------------------
require __DIR__.'/../public/index.php';

// Kirim isi response ke Vercel.
echo ob_get_clean();
