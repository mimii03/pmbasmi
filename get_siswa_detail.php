<?php
// get_siswa_detail.php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit('<div class="alert alert-danger">Unauthorized access</div>');
}

$host = 'localhost';
$dbname = 'pmb_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($siswa) {
        // Ambil nilai dari nilai_nim jika ada
        $nilai = '';
        if (isset($siswa['nilai_nim']) && $siswa['nilai_nim'] != 'NULL') {
            $parts = explode(' ', $siswa['nilai_nim']);
            $nilai = $parts[0];
            $nim = $parts[1] ?? '';
        }
        
        $status_badge = '';
        if ($siswa['status_pendaftaran'] == 'aktif') {
            $status_badge = '<span class="badge bg-success">AKTIF / LULUS</span>';
        } elseif ($siswa['status_pendaftaran'] == 'tidak_lulus') {
            $status_badge = '<span class="badge bg-danger">TIDAK LULUS</span>';
        } else {
            $status_badge = '<span class="badge bg-warning">' . strtoupper($siswa['status_pendaftaran']) . '</span>';
        }
        
        echo "
        <div class='row'>
            <div class='col-md-6'>
                <table class='table table-bordered'>
                    <tr><th width='40%'>NISN</th><td><strong>{$siswa['nisn']}</strong></td></tr>
                    <tr><th>Nama Lengkap</th><td><strong>{$siswa['nama_lengkap']}</strong></td></tr>
                    <tr><th>Jenis Kelamin</th><td>{$siswa['jenis_kelamin']}</td></tr>
                    <tr><th>Tanggal Lahir</th><td>{$siswa['tanggal_lahir']}</td></tr>
                    <tr><th>Asal Sekolah</th><td>{$siswa['asal_sekolah']}</td></tr>
                </table>
            </div>
            <div class='col-md-6'>
                <table class='table table-bordered'>
                    <tr><th width='40%'>Provinsi</th><td>{$siswa['provinsi']}</td></tr>
                    <tr><th>Kota/Kab</th><td>{$siswa['kota_kab']}</td></tr>
                    <tr><th>Jurusan</th><td>{$siswa['jurusan']}</td></tr>
                    <tr><th>Alamat</th><td>{$siswa['alamat']}</td></tr>
                    <tr><th>Nilai</th><td><span class='badge bg-primary'>{$nilai}</span></td></tr>
                    <tr><th>Status</th><td>{$status_badge}</td></tr>
                    <tr><th>Tanggal Daftar</th><td>" . date('d F Y H:i', strtotime($siswa['created_at'])) . "</td></tr>
                </table>
            </div>
        </div>";
    } else {
        echo "<div class='alert alert-warning'>Data siswa tidak ditemukan</div>";
    }
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
}
?>