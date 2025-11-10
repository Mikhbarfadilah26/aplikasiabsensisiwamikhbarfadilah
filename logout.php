<?php
// logout.php

// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua variabel sesi
$_SESSION = array();

// Hapus sesi cookie, jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Akhiri sesi
session_destroy();

// Kembali ke halaman welcome
header('Location: index.php?halaman=welcome');
exit;
