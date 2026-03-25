<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $provinsi = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $kota_kab = mysqli_real_escape_string($conn, $_POST['kota_kab']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $asal_sekolah = mysqli_real_escape_string($conn, $_POST['asal_sekolah']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $nomor_tes = 'TEST' . date('Ymd') . str_pad($user_id, 4, '0', STR_PAD_LEFT);
    
    $query = "UPDATE users SET 
                nama_lengkap = '$nama_lengkap',
                nisn = '$nisn',
                jenis_kelamin = '$jenis_kelamin',
                provinsi = '$provinsi',
                kota_kab = '$kota_kab',
                tanggal_lahir = '$tanggal_lahir',
                jurusan = '$jurusan',
                asal_sekolah = '$asal_sekolah',
                alamat = '$alamat',
                nomor_tes = '$nomor_tes',
                status_pendaftaran = 'menunggu_ujian'
              WHERE id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}

$provinces = [
    'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau',
    'Jambi', 'Bengkulu', 'Sumatera Selatan', 'Kepulauan Bangka Belitung',
    'Lampung', 'Banten', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah',
    'DI Yogyakarta', 'Jawa Timur', 'Bali', 'Nusa Tenggara Barat',
    'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah',
    'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
    'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan',
    'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat', 'Maluku',
    'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Tengah', 'Papua Pegunungan'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 50px 0;
        }
        
        .form-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .form-container h2 {
            color: #667eea;
            margin-bottom: 30px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="text-center">Formulir Pendaftaran</h2>
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Lengkap *</label>
                                <input type="text" class="form-control" name="nama_lengkap" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>NISN *</label>
                                <input type="text" class="form-control" name="nisn" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jenis Kelamin *</label>
                                <select class="form-control" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tanggal Lahir *</label>
                                <input type="date" class="form-control" name="tanggal_lahir" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Provinsi *</label>
                                <select class="form-control" name="provinsi" required>
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach($provinces as $province): ?>
                                        <option value="<?php echo $province; ?>"><?php echo $province; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kota/Kabupaten *</label>
                                <input type="text" class="form-control" name="kota_kab" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jurusan Pilihan *</label>
                                <select class="form-control" name="jurusan" required>
                                    <option value="">Pilih Jurusan</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                    <option value="Teknik Komputer">Teknik Komputer</option>
                                    <option value="Manajemen Informatika">Manajemen Informatika</option>
                                    <option value="Teknologi Informasi">Teknologi Informasi</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Asal Sekolah *</label>
                                <input type="text" class="form-control" name="asal_sekolah" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Alamat Lengkap *</label>
                                <textarea class="form-control" name="alamat" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-gradient">Simpan dan Dapatkan Nomor Tes</button>
                            <a href="dashboard.php" class="btn btn-secondary ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>