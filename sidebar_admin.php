<div class="sidebar animate__animated animate__fadeInLeft">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-tools"></i></div>
        <span>si tukang.</span>
    </div>
    
    <div class="menu-section">
        <small class="menu-label">MENU UTAMA</small>
        <nav>
            <a href="dashboard_admin.php" class="menu-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> Overview
            </a>
            <a href="admin_kategori.php" class="menu-link <?= basename($_SERVER['PHP_SELF']) == 'admin_kategori.php' ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Kategori Jasa
            </a>
            <a href="admin_verifikasi.php" class="menu-link <?= basename($_SERVER['PHP_SELF']) == 'admin_verifikasi.php' ? 'active' : '' ?>">
                <i class="fas fa-shield-alt"></i> Verifikasi Tukang
            </a>
        </nav>
    </div>

    <div class="menu-section">
        <small class="menu-label">LAPORAN & KEUANGAN</small>
        <nav>
            <a href="admin_transaksi.php" class="menu-link <?= basename($_SERVER['PHP_SELF']) == 'admin_transaksi.php' ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Log Transaksi
            </a>
            <a href="admin_komisi.php" class="menu-link <?= basename($_SERVER['PHP_SELF']) == 'admin_komisi.php' ? 'active' : '' ?>">
                <i class="fas fa-wallet"></i> Komisi Admin
            </a>
        </nav>
    </div>

    <div class="menu-section" style="margin-top: auto;">
        <nav>
            <a href="admin_profil.php" class="menu-link <?= basename($_SERVER['PHP_SELF']) == 'admin_profil.php' ? 'active' : '' ?>">
                <i class="fas fa-user-circle"></i> Profil Saya
            </a>
            <a href="logout.php" class="menu-link logout-btn">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </nav>
    </div>
</div>

<style>
    /* Sidebar Layout - Navy Dark Theme */
    .sidebar { 
        width: 240px; 
        background: #0f172a; /* Navy Dark Premium */
        color: #f8fafc; 
        height: 100vh; 
        position: fixed; 
        padding: 20px 12px; 
        display: flex;
        flex-direction: column;
        z-index: 1000;
        border-right: 1px solid rgba(255,255,255,0.05);
    }

    .sidebar-brand { 
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 15px 30px 15px;
    }

    .brand-icon {
        background: #3b82f6;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 14px;
    }

    .sidebar-brand span { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; }

    /* Menu Sectioning */
    .menu-section { margin-bottom: 20px; }
    .menu-label { 
        font-size: 10px; 
        font-weight: 700; 
        color: #64748b; 
        padding-left: 15px; 
        margin-bottom: 8px; 
        display: block; 
        letter-spacing: 1px;
    }

    /* Compact Menu Links */
    .menu-link { 
        text-decoration: none; 
        color: #94a3b8; 
        padding: 10px 15px; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        border-radius: 10px; 
        margin-bottom: 4px; 
        transition: all 0.2s ease; 
        font-size: 13px; 
        font-weight: 500;
    }

    .menu-link i { font-size: 15px; width: 20px; text-align: center; }

    /* Active & Hover States */
    .menu-link:hover { background: rgba(255,255,255,0.03); color: #f8fafc; }
    
    .menu-link.active { 
        background: #3b82f6; 
        color: white; 
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .logout-btn { color: #f87171 !important; margin-top: 5px; }
    .logout-btn:hover { background: rgba(248, 113, 113, 0.1) !important; }
</style>