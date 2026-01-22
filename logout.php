<?php
include 'config.php';

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke index dengan pesan logout sukses
header("location:index.php?status=success&pesan=Anda telah keluar sistem.&icon=info");
exit;
?>