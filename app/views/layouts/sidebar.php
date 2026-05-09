    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li>
                <a href="<?= BASE_URL ?>/dashboard" class="<?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            
            <?php if (Session::getRole() === 'admin'): ?>
                <!-- Admin Menu -->
                <div class="sidebar-divider"></div>
                <div class="sidebar-heading">Master Data</div>
                
                <li>
                    <a href="<?= BASE_URL ?>/golongan" class="<?= ($currentPage ?? '') === 'golongan' ? 'active' : '' ?>">
                        <i class="fas fa-layer-group"></i> Golongan Jabatan
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/divisi" class="<?= ($currentPage ?? '') === 'divisi' ? 'active' : '' ?>">
                        <i class="fas fa-building"></i> Divisi
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/jabatan" class="<?= ($currentPage ?? '') === 'jabatan' ? 'active' : '' ?>">
                        <i class="fas fa-briefcase"></i> Jabatan
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/pekerja" class="<?= ($currentPage ?? '') === 'pekerja' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Data Karyawan
                    </a>
                </li>
                
                <div class="sidebar-divider"></div>
                <div class="sidebar-heading">Pengajuan</div>
                <li>
                    <a href="<?= BASE_URL ?>/approval/semua" class="<?= ($currentPage ?? '') === 'approval-semua' ? 'active' : '' ?>">
                        <i class="fas fa-file-alt"></i> Semua Pengajuan
                    </a>
                </li>
                
                <div class="sidebar-divider"></div>
                <div class="sidebar-heading">Laporan</div>
                
                <li>
                    <a href="<?= BASE_URL ?>/laporan/pengajuan" class="<?= ($currentPage ?? '') === 'laporan' ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i> Laporan Pengajuan
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/laporan/pekerja" class="<?= ($currentPage ?? '') === 'laporan-pekerja' ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i> Laporan Karyawan
                    </a>
                </li>
                
            <?php elseif (Session::getRole() === 'pekerja'): ?>
                <!-- Pekerja Menu -->
                <div class="sidebar-divider"></div>
                <div class="sidebar-heading">Pengajuan</div>
                
                <li>
                    <a href="<?= BASE_URL ?>/pengajuan/create" class="<?= ($currentPage ?? '') === 'pengajuan-create' ? 'active' : '' ?>">
                        <i class="fas fa-plus-circle"></i> Ajukan Kenaikan Golongan
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/pengajuan" class="<?= ($currentPage ?? '') === 'pengajuan' ? 'active' : '' ?>">
                        <i class="fas fa-list"></i> Pengajuan Saya
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/pengajuan/riwayat" class="<?= ($currentPage ?? '') === 'riwayat' ? 'active' : '' ?>">
                        <i class="fas fa-history"></i> Riwayat Pengajuan
                    </a>
                </li>
                
            <?php elseif (in_array(Session::getRole(), ['atasan', 'manager', 'kepala_wilayah'])): ?>
                <!-- Approver Menu -->
                <div class="sidebar-divider"></div>
                <div class="sidebar-heading">Approval</div>
                
                <li>
                    <a href="<?= BASE_URL ?>/approval" class="<?= ($currentPage ?? '') === 'approval' ? 'active' : '' ?>">
                        <i class="fas fa-check-circle"></i> Pending Approval
                        <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                            <span class="badge bg-danger float-end"><?= $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/approval/riwayat" class="<?= ($currentPage ?? '') === 'approval-riwayat' ? 'active' : '' ?>">
                        <i class="fas fa-history"></i> Riwayat Approval
                    </a>
                </li>
                
                <?php if (Session::getRole() === 'atasan'): ?>
                    <div class="sidebar-divider"></div>
                    <div class="sidebar-heading">Pengajuan</div>
                    
                    <li>
                        <a href="<?= BASE_URL ?>/approval/semua" class="<?= ($currentPage ?? '') === 'approval-semua' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Semua Pengajuan Bawahan
                        </a>
                    </li>
                <?php else: ?>
                    <div class="sidebar-divider"></div>
                    <div class="sidebar-heading">Pengajuan</div>
                    
                    <li>
                        <a href="<?= BASE_URL ?>/pengajuan" class="<?= ($currentPage ?? '') === 'pengajuan' ? 'active' : '' ?>">
                            <i class="fas fa-list"></i> Semua Pengajuan
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if (Session::getRole() === 'kepala_wilayah'): ?>
                    <div class="sidebar-divider"></div>
                    <div class="sidebar-heading">Laporan</div>
                    
                    <li>
                        <a href="<?= BASE_URL ?>/laporan/pengajuan" class="<?= ($currentPage ?? '') === 'laporan' ? 'active' : '' ?>">
                            <i class="fas fa-chart-bar"></i> Laporan Pengajuan
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Common Menu -->
            <div class="sidebar-divider"></div>
            
            <li>
                <a href="<?= BASE_URL ?>/profil" class="<?= ($currentPage ?? '') === 'profil' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Profil Saya
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>/auth/logout" class="text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <?php
        // Display flash message
        $flash = Session::getFlash();
        if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                <?= Helper::escape($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
