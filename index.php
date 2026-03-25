<?php include 'header.php'; ?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        min-height: 80vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
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
    
    .btn-outline-light {
        border-radius: 50px;
        padding: 12px 30px;
    }
    
    .about-section, .jalur-section {
        padding: 80px 0;
    }
    
    .feature-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .feature-icon i {
        font-size: 40px;
        color: white;
    }
    
    .jalur-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .jalur-card:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 style="font-size: 3.5rem; font-weight: 700;">Selamat Datang di PMB Online</h1>
                <p class="mt-3" style="font-size: 1.2rem;">Penerimaan Mahasiswa Baru Universitas PRISMA Tahun Akademik 2026/2027</p>
                <div class="mt-4">
                    <a href="#jalur" class="btn btn-gradient me-3">Daftar Sekarang</a>
                    <a href="#about" class="btn btn-outline-light">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Tentang PMB Online</h2>
            <p class="text-muted">PRISMA adalah Platform Registrasi & Informasi Seleksi Mahasiswa. <br>
                Sistem Penerimaan Mahasiswa Baru yang Modern dan Efisien</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4>Proses Cepat</h4>
                    <p class="text-muted">Pendaftaran online yang cepat dan mudah, tanpa ribet</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4>Ujian Online</h4>
                    <p class="text-muted">Ikuti ujian seleksi secara online dari mana saja</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h4>Status Mahasiswa</h4>
                    <p class="text-muted">Dapatkan status mahasiswa yang bisa dicetak</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jalur Pendaftaran Section -->
<section id="jalur" class="jalur-section" style="background: #f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Jalur Pendaftaran</h2>
            <p class="text-muted">Pilih jalur pendaftaran yang sesuai dengan Anda</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="jalur-card" onclick="location.href='register.php'">
                    <i class="fas fa-graduation-cap" style="font-size: 50px; margin-bottom: 20px;"></i>
                    <h3>Seleksi Akademik</h3>
                    <p>Jalur seleksi berdasarkan tes akademik online</p>
                    <button class="btn btn-light mt-3">Daftar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>