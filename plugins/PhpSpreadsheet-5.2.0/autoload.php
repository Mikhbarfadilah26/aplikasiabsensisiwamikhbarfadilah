<?php
// Manual autoload PhpSpreadsheet tanpa Composer
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $base_dir = __DIR__ . '/src/PhpSpreadsheet/';
    $len = strlen($prefix);

    // Cek apakah class pakai namespace PhpSpreadsheet
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Ganti namespace jadi path file
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
