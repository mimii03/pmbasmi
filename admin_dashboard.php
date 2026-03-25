<?php
// admin_dashboard.php
session_start();

// Cek apakah admin sudah login (menggunakan session dari tabel admins)
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    header("Location: admin_login.php");
    exit();
}

// Koneksi database
$host = 'localhost';
$dbname = 'pmb_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Handle update status kelulusan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];
    
    try {
        if ($status == 'lulus') {
            // Siswa lulus seleksi (belum daftar ulang)
            $stmt = $pdo->prepare("UPDATE users SET status_pendaftaran = 'lulus', status_ujian = 'selesai' WHERE id = ?");
            $stmt->execute([$user_id]);
        } elseif ($status == 'aktif') {
            // Siswa sudah daftar ulang menjadi mahasiswa aktif
            $stmt = $pdo->prepare("UPDATE users SET status_pendaftaran = 'aktif', status_ujian = 'selesai' WHERE id = ?");
            $stmt->execute([$user_id]);
        } elseif ($status == 'tidak_lulus') {
            // Siswa tidak lulus
            $stmt = $pdo->prepare("UPDATE users SET status_pendaftaran = 'tidak_lulus', status_ujian = 'selesai' WHERE id = ?");
            $stmt->execute([$user_id]);
        } elseif ($status == 'proses') {
            // Siswa dalam proses (belum ujian)
            $stmt = $pdo->prepare("UPDATE users SET status_pendaftaran = 'belum_isi', status_ujian = 'belum' WHERE id = ?");
            $stmt->execute([$user_id]);
        }
        
        $_SESSION['success'] = "Status berhasil diupdate!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    $redirect_tab = isset($_POST['current_tab']) ? $_POST['current_tab'] : 'siswa_proses';
    header("Location: admin_dashboard.php?tab=" . $redirect_tab);
    exit();
}

// Handle update daftar ulang (ubah dari lulus menjadi aktif)
if (isset($_GET['daftar_ulang'])) {
    $id = $_GET['daftar_ulang'];
    $stmt = $pdo->prepare("UPDATE users SET status_pendaftaran = 'aktif' WHERE id = ? AND status_pendaftaran = 'lulus'");
    $stmt->execute([$id]);
    header("Location: admin_dashboard.php?tab=siswa_lulus");
    exit();
}

// Handle hapus pengguna
if (isset($_GET['hapus_user'])) {
    $id = $_GET['hapus_user'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->execute([$id]);
    header("Location: admin_dashboard.php?tab=pengguna");
    exit();
}

// Handle tambah pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_user'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    
    $stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, username, password, role, status_pendaftaran, status_ujian, created_at) VALUES (?, ?, ?, ?, 'belum_isi', 'belum', NOW())");
    $stmt->execute([$nama_lengkap, $username, $password, $role]);
    header("Location: admin_dashboard.php?tab=pengguna");
    exit();
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'siswa_proses';
?>

<?php include 'header.php'; ?>

<style>
    .admin-nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
        padding: 12px 20px;
    }
    .admin-nav-tabs .nav-link:hover {
        border-color: #dee2e6 #dee2e6 #fff;
        color: #667eea;
    }
    .admin-nav-tabs .nav-link.active {
        color: #667eea;
        font-weight: 600;
        border-bottom: 3px solid #667eea;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .card-header h5, .card-header p {
        color: white;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .nilai-badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .nilai-lulus {
        background-color: #28a745;
        color: white;
    }
    .nilai-aktif {
        background-color: #17a2b8;
        color: white;
    }
    .nilai-tidak-lulus {
        background-color: #dc3545;
        color: white;
    }
    .nilai-proses {
        background-color: #ffc107;
        color: #000;
    }
    .status-select {
        width: 140px;
        display: inline-block;
        font-size: 12px;
        padding: 3px 5px;
    }
    .btn-update-status {
        padding: 2px 8px;
        font-size: 11px;
    }
    .badge-lulus {
        background-color: #28a745;
    }
    .badge-aktif {
        background-color: #17a2b8;
    }
    .btn-daftar-ulang {
        background-color: #ffc107;
        color: #000;
        border: none;
        padding: 2px 8px;
        font-size: 11px;
    }
    .btn-daftar-ulang:hover {
        background-color: #e0a800;
    }
</style>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                    </h2>
                    <p class="text-muted">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_nama'] ?? $_SESSION['admin_username']); ?>!</p>
                </div>
                <div>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs admin-nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo $tab == 'siswa_proses' ? 'active' : ''; ?>" href="?tab=siswa_proses">
                <i class="fas fa-clock"></i> Siswa Dalam Proses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab == 'siswa_lulus' ? 'active' : ''; ?>" href="?tab=siswa_lulus">
                <i class="fas fa-check-circle"></i> Siswa Lulus Seleksi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab == 'mahasiswa_aktif' ? 'active' : ''; ?>" href="?tab=mahasiswa_aktif">
                <i class="fas fa-graduation-cap"></i> Mahasiswa Aktif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab == 'siswa_tidak_lulus' ? 'active' : ''; ?>" href="?tab=siswa_tidak_lulus">
                <i class="fas fa-times-circle"></i> Siswa Tidak Lulus
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tab == 'pengguna' ? 'active' : ''; ?>" href="?tab=pengguna">
                <i class="fas fa-users"></i> Manajemen Pengguna
            </a>
        </li>
    </ul>
    
    <!-- Content -->
    <?php if ($tab == 'siswa_proses'): ?>
        <!-- Siswa Dalam Proses (Belum Ujian) -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Data Siswa Dalam Proses</h5>
                <p class="mb-0 mt-1 small">Siswa yang belum ujian atau belum lulus/tidak lulus</p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchProses" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-warning p-2">Total: <?php 
                            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND status_pendaftaran NOT IN ('lulus', 'aktif', 'tidak_lulus')");
                            echo $stmt->fetchColumn(); 
                        ?> siswa</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableProses">
                        <thead class="table-light">
                            32-
                                <th width="50">No</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Asal Sekolah</th>
                                <th width="100">Nilai</th>
                                <th width="150">Status Ujian</th>
                                <th width="150">Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM users WHERE role = 'siswa' AND status_pendaftaran NOT IN ('lulus', 'aktif', 'tidak_lulus') ORDER BY created_at DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $nilai = isset($row['nilai']) && !empty($row['nilai']) ? number_format($row['nilai'], 2) : '-';
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$row['nisn']}</td>";
                                echo "<td><strong>{$row['nama_lengkap']}</strong></td>";
                                echo "<td>{$row['asal_sekolah']}</td>";
                                echo "<td class='text-center'><span class='badge nilai-proses nilai-badge'>{$nilai}</span></td>";
                                echo "<td class='text-center'><span class='badge bg-secondary'>{$row['status_ujian']}</span></td>";
                                echo "<td class='text-center'><span class='badge bg-info'>{$row['status_pendaftaran']}</span></td>";
                                echo "<td class='text-center'>
                                        <button class='btn btn-sm btn-info me-1' onclick='lihatDetail({$row['id']})'><i class='fas fa-eye'></i></button>
                                        <form method='POST' style='display:inline-block'>
                                            <input type='hidden' name='user_id' value='{$row['id']}'>
                                            <input type='hidden' name='current_tab' value='siswa_proses'>
                                            <select name='status' class='form-select status-select'>
                                                <option value='lulus'>Lulus Seleksi</option>
                                                <option value='tidak_lulus'>Tidak Lulus</option>
                                                <option value='proses' selected>Proses</option>
                                            </select>
                                            <button type='submit' name='update_status' class='btn btn-sm btn-primary btn-update-status'>Update</button>
                                        </form>
                                       </td>";
                                echo "</tr>";
                                $no++;
                            }
                            if ($no == 1) {
                                echo "<tr><td colspan='8' class='text-center text-muted'>Belum ada data siswa dalam proses</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    <?php elseif ($tab == 'siswa_lulus'): ?>
        <!-- Siswa Lulus Seleksi (Belum Daftar Ulang) -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-check-circle"></i> Siswa Lulus Seleksi</h5>
                <p class="mb-0 mt-1 small">Peserta yang lulus seleksi dan menunggu daftar ulang</p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchLulus" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-success p-2">Total: <?php 
                            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND status_pendaftaran = 'lulus'");
                            echo $stmt->fetchColumn(); 
                        ?> siswa</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableLulus">
                        <thead class="table-light">
                            32-
                                <th width="50">No</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Asal Sekolah</th>
                                <th width="100">Nilai</th>
                                <th width="150">Status Ujian</th>
                                <th width="150">Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM users WHERE role = 'siswa' AND status_pendaftaran = 'lulus' ORDER BY nilai DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $nilai = isset($row['nilai']) && !empty($row['nilai']) ? number_format($row['nilai'], 2) : '-';
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$row['nisn']}</td>";
                                echo "<td><strong>{$row['nama_lengkap']}</strong></td>";
                                echo "<td>{$row['asal_sekolah']}</td>";
                                echo "<td class='text-center'><span class='badge nilai-lulus nilai-badge'>{$nilai}</span></td>";
                                echo "<td class='text-center'><span class='badge bg-success'>{$row['status_ujian']}</span></td>";
                                echo "<td class='text-center'><span class='badge badge-lulus'>LULUS SELEKSI</span></td>";
                                echo "<td class='text-center'>
                                        <button class='btn btn-sm btn-info me-1' onclick='lihatDetail({$row['id']})'><i class='fas fa-eye'></i></button>
                                        <a href='?daftar_ulang={$row['id']}' class='btn btn-sm btn-daftar-ulang me-1' onclick='return confirm(\"Konfirmasi daftar ulang? Siswa akan menjadi mahasiswa aktif.\")'>
                                            <i class='fas fa-edit'></i> Daftar Ulang
                                        </a>
                                        <form method='POST' style='display:inline-block'>
                                            <input type='hidden' name='user_id' value='{$row['id']}'>
                                            <input type='hidden' name='current_tab' value='siswa_lulus'>
                                            <select name='status' class='form-select status-select'>
                                                <option value='lulus' selected>Lulus</option>
                                                <option value='tidak_lulus'>Tidak Lulus</option>
                                                <option value='proses'>Proses</option>
                                            </select>
                                            <button type='submit' name='update_status' class='btn btn-sm btn-warning btn-update-status'>Update</button>
                                        </form>
                                       </td>";
                                echo "</tr>";
                                $no++;
                            }
                            if ($no == 1) {
                                echo "<tr><td colspan='8' class='text-center text-muted'>Belum ada siswa lulus seleksi</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    <?php elseif ($tab == 'mahasiswa_aktif'): ?>
        <!-- Mahasiswa Aktif (Sudah Daftar Ulang) -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Mahasiswa Aktif</h5>
                <p class="mb-0 mt-1 small">Peserta yang lulus seleksi dan sudah daftar ulang menjadi mahasiswa aktif</p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchAktif" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-info p-2">Total: <?php 
                            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND status_pendaftaran = 'aktif'");
                            echo $stmt->fetchColumn(); 
                        ?> mahasiswa</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableAktif">
                        <thead class="table-light">
                            32-
                                <th width="50">No</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Asal Sekolah</th>
                                <th width="100">Nilai</th>
                                <th width="150">Status Ujian</th>
                                <th width="150">Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM users WHERE role = 'siswa' AND status_pendaftaran = 'aktif' ORDER BY nilai DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $nilai = isset($row['nilai']) && !empty($row['nilai']) ? number_format($row['nilai'], 2) : '-';
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$row['nisn']}</td>";
                                echo "<td><strong>{$row['nama_lengkap']}</strong></td>";
                                echo "<td>{$row['asal_sekolah']}</td>";
                                echo "<td class='text-center'><span class='badge nilai-aktif nilai-badge'>{$nilai}</span></td>";
                                echo "<td class='text-center'><span class='badge bg-info'>{$row['status_ujian']}</span></td>";
                                echo "<td class='text-center'><span class='badge badge-aktif'>MAHASISWA AKTIF</span></td>";
                                echo "<td class='text-center'>
                                        <button class='btn btn-sm btn-info' onclick='lihatDetail({$row['id']})'><i class='fas fa-eye'></i></button>
                                        <form method='POST' style='display:inline-block'>
                                            <input type='hidden' name='user_id' value='{$row['id']}'>
                                            <input type='hidden' name='current_tab' value='mahasiswa_aktif'>
                                            <select name='status' class='form-select status-select'>
                                                <option value='aktif' selected>Mahasiswa Aktif</option>
                                                <option value='lulus'>Lulus Seleksi</option>
                                                <option value='tidak_lulus'>Tidak Lulus</option>
                                                <option value='proses'>Proses</option>
                                            </select>
                                            <button type='submit' name='update_status' class='btn btn-sm btn-warning btn-update-status'>Update</button>
                                        </form>
                                       </td>";
                                echo "</tr>";
                                $no++;
                            }
                            if ($no == 1) {
                                echo "<tr><td colspan='8' class='text-center text-muted'>Belum ada mahasiswa aktif</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    <?php elseif ($tab == 'siswa_tidak_lulus'): ?>
        <!-- Siswa Tidak Lulus -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-times-circle"></i> Siswa Tidak Lulus</h5>
                <p class="mb-0 mt-1 small">Peserta yang tidak lulus seleksi</p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchTidakLulus" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-danger p-2">Total: <?php 
                            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'siswa' AND status_pendaftaran = 'tidak_lulus'");
                            echo $stmt->fetchColumn(); 
                        ?> siswa</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tableTidakLulus">
                        <thead class="table-light">
                            32-
                                <th width="50">No</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Asal Sekolah</th>
                                <th width="100">Nilai</th>
                                <th width="150">Status Ujian</th>
                                <th width="150">Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM users WHERE role = 'siswa' AND status_pendaftaran = 'tidak_lulus' ORDER BY nilai DESC");
                            $no = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $nilai = isset($row['nilai']) && !empty($row['nilai']) ? number_format($row['nilai'], 2) : '-';
                                echo "<tr>";
                                echo "<td>{$no}</td>";
                                echo "<td>{$row['nisn']}</td>";
                                echo "<td><strong>{$row['nama_lengkap']}</strong></td>";
                                echo "<td>{$row['asal_sekolah']}</td>";
                                echo "<td class='text-center'><span class='badge nilai-tidak-lulus nilai-badge'>{$nilai}</span></td>";
                                echo "<td class='text-center'><span class='badge bg-danger'>{$row['status_ujian']}</span></td>";
                                echo "<td class='text-center'><span class='badge bg-danger'>TIDAK LULUS</span></td>";
                                echo "<td class='text-center'>
                                        <button class='btn btn-sm btn-info me-1' onclick='lihatDetail({$row['id']})'><i class='fas fa-eye'></i></button>
                                        <form method='POST' style='display:inline-block'>
                                            <input type='hidden' name='user_id' value='{$row['id']}'>
                                            <input type='hidden' name='current_tab' value='siswa_tidak_lulus'>
                                            <select name='status' class='form-select status-select'>
                                                <option value='lulus'>Lulus Seleksi</option>
                                                <option value='tidak_lulus' selected>Tidak Lulus</option>
                                                <option value='proses'>Proses</option>
                                            </select>
                                            <button type='submit' name='update_status' class='btn btn-sm btn-primary btn-update-status'>Update</button>
                                        </form>
                                       </td>";
                                echo "</tr>";
                                $no++;
                            }
                            if ($no == 1) {
                                echo "<tr><td colspan='8' class='text-center text-muted'>Belum ada siswa tidak lulus</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    <?php elseif ($tab == 'pengguna'): ?>
        <!-- Manajemen Pengguna (sama seperti sebelumnya) -->
        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Tambah Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-control">
                                    <option value="siswa">Siswa</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <button type="submit" name="tambah_user" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="searchUser" class="form-control" placeholder="Cari pengguna...">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tableUser">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Lengkap</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th width="80">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
                                    $no = 1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $roleClass = $row['role'] == 'admin' ? 'danger' : ($row['role'] == 'user' ? 'info' : 'success');
                                        echo "<tr>";
                                        echo "<td>{$no}</td>";
                                        echo "<td><strong>{$row['nama_lengkap']}</strong></td>";
                                        echo "<td>{$row['username']}</td>";
                                        echo "<td><span class='badge bg-{$roleClass}'>{$row['role']}</span></td>";
                                        echo "<td class='text-center'>";
                                        if ($row['role'] != 'admin') {
                                            echo "<a href='?tab=pengguna&hapus_user={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus?\")'><i class='fas fa-trash'></i></a>";
                                        } else {
                                            echo "<span class='text-muted'><i class='fas fa-shield-alt'></i></span>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Detail Siswa -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-user-graduate"></i> Detail Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterTable(tableId, searchText) {
    const table = document.getElementById(tableId);
    if (!table) return;
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        let textContent = '';
        const cells = row.getElementsByTagName('td');
        for (let cell of cells) {
            textContent += cell.textContent.toLowerCase() + ' ';
        }
        if (textContent.includes(searchText.toLowerCase())) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

document.getElementById('searchProses')?.addEventListener('keyup', function() {
    filterTable('tableProses', this.value);
});
document.getElementById('searchLulus')?.addEventListener('keyup', function() {
    filterTable('tableLulus', this.value);
});
document.getElementById('searchAktif')?.addEventListener('keyup', function() {
    filterTable('tableAktif', this.value);
});
document.getElementById('searchTidakLulus')?.addEventListener('keyup', function() {
    filterTable('tableTidakLulus', this.value);
});
document.getElementById('searchUser')?.addEventListener('keyup', function() {
    filterTable('tableUser', this.value);
});

function lihatDetail(id) {
    fetch(`get_siswa_detail.php?id=${id}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('detailContent').innerHTML = data;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        })
        .catch(error => {
            document.getElementById('detailContent').innerHTML = '<div class="alert alert-danger">Error loading data</div>';
        });
}
</script>

<?php include 'footer.php'; ?>