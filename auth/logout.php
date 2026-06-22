<?php
// auth/logout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua session user
unset($_SESSION['user_logged_in']);
unset($_SESSION['user_id']);
unset($_SESSION['user_nama']);
unset($_SESSION['user_email']);

// Destroy session
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keluar — Traveloop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Keluar',
                text: 'Sampai jumpa kembali di petualangan berikutnya!',
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-4 shadow-lg'
                }
            }).then(() => {
                // Redirect ke Beranda setelah animasi selesai
                window.location.href = '../index.php';
            });
        });
    </script>
</body>
</html>