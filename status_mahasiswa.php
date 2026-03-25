<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($user['status_pendaftaran'] != 'aktif') {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Mahasiswa - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 50px 0;
        }
        
        .status-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            animation: fadeInUp 0.5s ease;
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
        
        .nim-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nim-badge h1 {
            font-family: monospace;
            letter-spacing: 3px;
            font-size: 32px;
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
        
        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .foto-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .foto-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
            background: #f0f0f0;
        }
        
        /* Style untuk print */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .status-card {
                box-shadow: none;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            .nim-badge {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="status-card">
                    <!-- Tombol Kembali dan Cetak (tidak ikut print) -->
                    <div class="no-print mb-3 text-end">
                        <a href="dashboard.php" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <button onclick="printOnlyCard()" class="btn btn-gradient">
                            <i class="fas fa-print"></i> Cetak Status Mahasiswa
                        </button>
                    </div>
                    
                    <!-- Area yang akan di-print -->
                    <div id="printArea">
                        <div class="foto-container">
                            <?php 
                            $foto_path = !empty($user['foto']) && file_exists($user['foto']) ? $user['foto'] : 'https://via.placeholder.com/150?text=Foto';
                            ?>
                            <img src="<?php echo $foto_path; ?>" alt="Foto Mahasiswa" onerror="this.src='https://via.placeholder.com/150?text=Foto'">
                        </div>
                        
                        <div class="nim-badge">
                            <h5>NIM Mahasiswa</h5>
                            <h1><?php echo $user['nim']; ?></h1>
                            <p class="mb-0">Terdaftar sebagai mahasiswa aktif</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="mb-3">Data Mahasiswa</h4>
                                <div class="info-row">
                                    <strong>Nama Lengkap:</strong> <?php echo $user['nama_lengkap']; ?>
                                </div>
                                <div class="info-row">
                                    <strong>NISN:</strong> <?php echo $user['nisn']; ?>
                                </div>
                                <div class="info-row">
                                    <strong>Jenis Kelamin:</strong> <?php echo ($user['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?>
                                </div>
                                <div class="info-row">
                                    <strong>Tanggal Lahir:</strong> <?php echo date('d F Y', strtotime($user['tanggal_lahir'])); ?>
                                </div>
                                <div class="info-row">
                                    <strong>Jurusan:</strong> <?php echo $user['jurusan']; ?>
                                </div>
                                <div class="info-row">
                                    <strong>Asal Sekolah:</strong> <?php echo $user['asal_sekolah']; ?>
                                </div>
                                <div class="info-row">
                                    <strong>Alamat:</strong> <?php echo $user['alamat']; ?>
                                </div>
                                <div class="info-row">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-success">Mahasiswa Aktif</span>
                                </div>
                                <div class="info-row">
                                    <strong>Nomor Tes:</strong> <?php echo $user['nomor_tes']; ?>
                                </div>
                                <div class="info-row">
                                    <strong>Tanggal Daftar:</strong> <?php echo date('d F Y', strtotime($user['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function printOnlyCard() {
            var printContents = document.getElementById('printArea').innerHTML;
            
            var printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Status Mahasiswa - <?php echo $user['nama_lengkap']; ?></title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body {
                            padding: 20px;
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        }
                        .nim-badge {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            color: white;
                            padding: 20px;
                            border-radius: 15px;
                            text-align: center;
                            margin-bottom: 30px;
                        }
                        .nim-badge h1 {
                            font-family: monospace;
                            letter-spacing: 3px;
                            font-size: 32px;
                        }
                        .info-row {
                            padding: 10px 0;
                            border-bottom: 1px solid #e0e0e0;
                        }
                        .foto-container {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .foto-container img {
                            width: 150px;
                            height: 150px;
                            border-radius: 50%;
                            object-fit: cover;
                            border: 3px solid #667eea;
                        }
                        @media print {
                            body {
                                padding: 0;
                                margin: 0;
                            }
                            .nim-badge {
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                ${printContents}
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>