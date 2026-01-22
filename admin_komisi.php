<?php
include 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:index.php?status=error&pesan=Akses Ditolak!&icon=error"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Komisi | Si Tukang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0056FF; --bg: #F4F7FE; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-size: 12px; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); display: flex; }
        .sidebar { width: 220px; background: var(--primary); color: white; height: 100vh; position: fixed; padding: 25px 15px; }
        .main-content { flex: 1; margin-left: 220px; padding: 30px; }
        .card-komisi { background: white; padding: 25px; border-radius: 12px; text-align: center; border-left: 4px solid #FCD34D; }
    </style>
</head>
<body>

<?php include 'sidebar_admin.php'; ?>

<div class="main-content">
    <div class="header animate__animated animate__fadeInDown" style="margin-bottom: 25px;">
        <h2 style="font-size: 16px; font-weight: 700;">Pendapatan Platform</h2>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="card-komisi animate__animated animate__zoomIn">
            <p style="color: #718096; margin-bottom: 10px;">Total Saldo Komisi (10%)</p>
            <h1 style="font-size: 24px; color: #0056FF;">Rp 0</h1>
        </div>
        <div class="card-komisi animate__animated animate__zoomIn" style="border-left-color: #10B981; animation-delay: 0.1s;">
            <p style="color: #718096; margin-bottom: 10px;">Transaksi Berhasil</p>
            <h1 style="font-size: 24px; color: #10B981;">0</h1>
        </div>
    </div>
</div>

</body>
</html>