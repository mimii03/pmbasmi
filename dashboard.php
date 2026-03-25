<?php
include 'config.php';
include 'header.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$user = mysqli_fetch_assoc($result);
?>

<style>
    .hero-banner {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        color: white;
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
        animation: moveBackground 20s linear infinite;
    }
    
    @keyframes moveBackground {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        margin-bottom: 20px;
        animation: fadeInUp 0.6s ease;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 20px;
        animation: fadeInUp 0.6s ease 0.1s;
        animation-fill-mode: both;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 30px;
        animation: fadeInUp 0.6s ease 0.2s;
        animation-fill-mode: both;
    }
    
    .welcome-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-top: -40px;
        position: relative;
        z-index: 10;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        animation: fadeInUp 0.6s ease 0.3s;
        animation-fill-mode: both;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .welcome-avatar {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }
    
    .welcome-avatar i {
        font-size: 35px;
        color: white;
    }
    
    .status-badge {
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
    }
    
    .dashboard-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .card-icon i {
        font-size: 30px;
        color: white;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: white;
    }
    
    .btn-outline-gradient {
        background: transparent;
        border: 2px solid #667eea;
        color: #667eea;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-outline-gradient:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }
    
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .nim-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        padding: 30px;
        color: white;
        text-align: center;
    }
    
    .nim-card h1 {
        font-family: monospace;
        letter-spacing: 3px;
        font-size: 32px;
        margin: 15px 0;
    }
    
    .step-timeline {
        display: flex;
        justify-content: space-between;
        margin: 30px 0;
        position: relative;
    }
    
    .step-timeline::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }
    
    .step-item {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    
    .step-circle {
        width: 60px;
        height: 60px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-weight: bold;
        font-size: 20px;
        color: #667eea;
        background: white;
    }
    
    .step-circle.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    .step-circle.completed {
        background: #28a745;
        border-color: transparent;
        color: white;
    }
    
    .step-label {
        font-size: 14px;
        color: #666;
    }
    
    .step-label.active {
        color: #667eea;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        .step-timeline {
            flex-direction: column;
        }
        .step-timeline::before {
            display: none;
        }
        .step-item {
            margin-bottom: 20px;
        }
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="container hero-content">
        <div class="row">
            <div class="col-lg-8">
                <div class="hero-badge">
                    <i class="fas fa-calendar-alt"></i> Pendaftaran Dibuka - 2026
                </div>
                <h1 class="hero-title">
                    Pendaftaran Mahasiswa Baru<br>
                    <span style="color: #667eea;">Universitas PRISMA</span> Telah Dibuka
                </h1>
                <p class="hero-subtitle">
                    Proses Seleksi Online & Transparan • Raih Masa Depan Gemilang Bersama Kami
                </p>
                <div class="d-flex gap-3">
                    <div><i class="fas fa-check-circle"></i> 100% Online</div>
                    <div><i class="fas fa-check-circle"></i> Transparan</div>
                    <div><i class="fas fa-check-circle"></i> Akreditasi Unggul</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <div class="welcome-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <h3 class="mb-0">Halo, <?php echo $user['nama_lengkap']; ?>!</h3>
                    <p class="text-muted mb-0">Selamat datang di portal PMB Universitas PRISMA</p>
                </div>
            </div>
            <div>
                <?php
                $status_class = '';
                $status_text = '';
                $status_icon = '';
                switch($user['status_pendaftaran']) {
                    case 'belum_isi':
                        $status_class = 'bg-warning';
                        $status_text = 'Belum Mengisi Formulir';
                        $status_icon = 'fa-edit';
                        break;
                    case 'menunggu_ujian':
                        $status_class = 'bg-info';
                        $status_text = 'Menunggu Ujian';
                        $status_icon = 'fa-clock';
                        break;
                    case 'lulus':
                        $status_class = 'bg-success';
                        $status_text = 'Lulus Seleksi';
                        $status_icon = 'fa-check-circle';
                        break;
                    case 'tidak_lulus':
                        $status_class = 'bg-danger';
                        $status_text = 'Tidak Lulus Seleksi';
                        $status_icon = 'fa-times-circle';
                        break;
                    case 'aktif':
                        $status_class = 'bg-success';
                        $status_text = 'Mahasiswa Aktif';
                        $status_icon = 'fa-id-card';
                        break;
                    default:
                        $status_class = 'bg-secondary';
                        $status_text = $user['status_pendaftaran'];
                        $status_icon = 'fa-info-circle';
                }
                ?>
                <span class="status-badge <?php echo $status_class; ?>">
                    <i class="fas <?php echo $status_icon; ?>"></i> <?php echo $status_text; ?>
                </span>
            </div>
        </div>
        
        <?php if($user['status_pendaftaran'] == 'aktif'): ?>
        <div class="alert alert-success mt-4 mb-0">
            <i class="fas fa-trophy"></i> <strong>Selamat!</strong> Kamu sudah terdaftar sebagai mahasiswa Universitas PRISMA.
            <br>NIM: <strong><?php echo $user['nim']; ?></strong>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Timeline Status -->
    <div class="dashboard-card mt-4">
        <h5 class="mb-4"><i class="fas fa-chart-line"></i> Tahapan Pendaftaran</h5>
        <div class="step-timeline">
            <div class="step-item">
                <div class="step-circle <?php echo ($user['status_pendaftaran'] != 'belum_isi') ? 'completed' : ''; ?>">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="step-label <?php echo ($user['status_pendaftaran'] != 'belum_isi') ? 'active' : ''; ?>">
                    Isi Formulir
                </div>
            </div>
            <div class="step-item">
                <div class="step-circle <?php echo ($user['status_pendaftaran'] == 'menunggu_ujian' || $user['status_pendaftaran'] == 'lulus' || $user['status_pendaftaran'] == 'tidak_lulus' || $user['status_pendaftaran'] == 'aktif') ? 'completed' : ''; ?>">
                    <i class="fas fa-pen"></i>
                </div>
                <div class="step-label <?php echo ($user['status_pendaftaran'] == 'menunggu_ujian' || $user['status_pendaftaran'] == 'lulus' || $user['status_pendaftaran'] == 'tidak_lulus' || $user['status_pendaftaran'] == 'aktif') ? 'active' : ''; ?>">
                    Ujian Seleksi
                </div>
            </div>
            <div class="step-item">
                <div class="step-circle <?php echo ($user['status_pendaftaran'] == 'lulus' || $user['status_pendaftaran'] == 'aktif') ? 'completed' : ''; ?>">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="step-label <?php echo ($user['status_pendaftaran'] == 'lulus' || $user['status_pendaftaran'] == 'aktif') ? 'active' : ''; ?>">
                    Daftar Ulang
                </div>
            </div>
            <div class="step-item">
                <div class="step-circle <?php echo ($user['status_pendaftaran'] == 'aktif') ? 'completed' : ''; ?>">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="step-label <?php echo ($user['status_pendaftaran'] == 'aktif') ? 'active' : ''; ?>">
                    Aktif Mahasiswa
                </div>
            </div>
        </div>
    </div>
    
    <!-- Konten Utama -->
    <div class="row">
        <?php if($user['status_pendaftaran'] == 'belum_isi'): ?>
            <div class="col-md-12">
                <div class="dashboard-card text-center py-5">
                    <div class="card-icon mx-auto">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4>Kamu belum mengisi form pendaftaran</h4>
                    <p class="text-muted">Silakan lengkapi data diri Anda untuk memulai proses seleksi</p>
                    <a href="form_pendaftaran.php" class="btn btn-gradient mt-3">
                        <i class="fas fa-edit"></i> Isi Form Pendaftaran
                    </a>
                </div>
            </div>
            
        <?php elseif($user['status_pendaftaran'] == 'menunggu_ujian'): ?>
            <div class="col-md-8">
                <div class="info-box">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div>
                            <h4 class="mb-2"><i class="fas fa-pen"></i> Ujian Seleksi Akademik</h4>
                            <p class="mb-0">Waktu pengerjaan: 30 menit • 10 Soal</p>
                            <p class="mb-0 mt-2"><strong>Nomor Tes:</strong> <?php echo $user['nomor_tes']; ?></p>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <a href="ujian.php" class="btn btn-light btn-lg">
                                <i class="fas fa-play"></i> Mulai Ujian
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h5><i class="fas fa-info-circle"></i> Petunjuk Ujian</h5>
                    <ul class="mt-3">
                        <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Ujian terdiri dari 10 soal pilihan ganda</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Waktu pengerjaan 30 menit</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Pastikan koneksi internet stabil</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Hasil ujian akan langsung muncul setelah submit</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h5><i class="fas fa-user"></i> Data Pendaftaran</h5>
                    <hr>
                    <p><strong>Nama Lengkap:</strong><br><?php echo $user['nama_lengkap']; ?></p>
                    <p><strong>NISN:</strong><br><?php echo $user['nisn']; ?></p>
                    <p><strong>Jurusan Pilihan:</strong><br><?php echo $user['jurusan']; ?></p>
                    <p><strong>Asal Sekolah:</strong><br><?php echo $user['asal_sekolah']; ?></p>
                    <hr>
                    <a href="form_pendaftaran.php" class="btn btn-outline-gradient w-100">
                        <i class="fas fa-edit"></i> Edit Data
                    </a>
                </div>
            </div>
            
        <?php elseif($user['status_pendaftaran'] == 'tidak_lulus'): ?>
            <!-- Tidak lulus seleksi - TANPA CETAK SERTIFIKAT -->
            <div class="col-md-12">
                <div class="dashboard-card text-center py-5">
                    <i class="fas fa-frown" style="font-size: 80px; color: #dc3545;"></i>
                    <h4 class="mt-3">Mohon maaf, Anda belum lulus seleksi</h4>
                    <p class="text-muted">Anda belum memenuhi standar kelulusan yang ditentukan.</p>
                    <p>Silakan coba lagi pada gelombang pendaftaran berikutnya.</p>
                </div>
            </div>
            
        <?php elseif($user['status_pendaftaran'] == 'lulus'): ?>
            <div class="col-md-12">
                <div class="dashboard-card text-center py-5">
                    <i class="fas fa-check-circle" style="font-size: 80px; color: #28a745;"></i>
                    <h4 class="mt-3">Selamat! Anda Lulus Seleksi Akademik</h4>
                    <p class="text-muted">Anda berhasil lulus seleksi masuk Universitas PRISMA.</p>
                    <p>Silakan melakukan daftar ulang untuk menjadi mahasiswa resmi Universitas PRISMA</p>
                    <a href="daftar_ulang.php" class="btn btn-gradient mt-3">
                        <i class="fas fa-file-signature"></i> Daftar Ulang Sekarang
                    </a>
                </div>
            </div>
            
        <?php elseif($user['status_pendaftaran'] == 'aktif'): ?>
            <div class="col-md-12">
                <div class="nim-card">
                    <i class="fas fa-id-card" style="font-size: 50px;"></i>
                    <h3 class="mt-3">Kamu sudah resmi menjadi mahasiswa</h3>
                    <h1><?php echo $user['nim']; ?></h1>
                    <p class="mb-0">Universitas PRISMA • Tahun Akademik <?php echo date('Y'); ?></p>
                    <div class="mt-4">
                        <a href="status_mahasiswa.php" class="btn btn-light">
                            <i class="fas fa-id-card"></i> Lihat Status Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mt-4">
                <div class="dashboard-card">
                    <h5><i class="fas fa-calendar-alt"></i> Informasi Akademik</h5>
                    <hr>
                    <p><strong>Tanggal Daftar:</strong> <?php echo date('d F Y', strtotime($user['created_at'])); ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-success">Mahasiswa Aktif</span></p>
                    <p><strong>Program Studi:</strong> <?php echo $user['jurusan']; ?></p>
                </div>
            </div>
            
            <div class="col-md-6 mt-4">
                <div class="dashboard-card">
                    <h5><i class="fas fa-graduation-cap"></i> Langkah Selanjutnya</h5>
                    <hr>
                    <ul>
                        <li class="mb-2">✓ Registrasi mata kuliah akan dibuka H-7 sebelum perkuliahan</li>
                        <li class="mb-2">✓ Persiapan orientasi mahasiswa baru (OSPEK)</li>
                        <li class="mb-2">✓ Cek jadwal perkuliahan di portal akademik</li>
                        <li class="mb-2">✓ Siapkan dokumen fisik untuk verifikasi akhir</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>