<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($user['status_pendaftaran'] != 'lulus' && $user['status_pendaftaran'] != 'tidak_lulus') {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .result-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
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
        
        .certificate {
            background: white;
            border: 2px solid #667eea;
            border-radius: 15px;
            padding: 40px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="result-card">
                    <?php if($user['status_pendaftaran'] == 'lulus'): ?>
                        <i class="fas fa-check-circle" style="font-size: 80px; color: #28a745;"></i>
                        <h2 class="mt-3">Selamat! Anda Lulus Seleksi</h2>
                        <p class="lead">Anda berhasil lulus seleksi akademik dengan baik.</p>
                        <div class="mt-4">
                            <a href="dashboard.php" class="btn btn-gradient">
                                <i class="fas fa-tachometer-alt"></i> Ke Dashboard
                            </a>
                        
                    <?php else: ?>
                        <i class="fas fa-times-circle" style="font-size: 80px; color: #dc3545;"></i>
                        <h2 class="mt-3">Mohon Maaf, Anda Belum Lulus</h2>
                        <p class="lead">Nilai Anda belum mencapai standar kelulusan yang ditentukan.</p>
                        <p>Silakan coba lagi pada gelombang pendaftaran berikutnya.</p>
                        <div class="mt-4">
                            <a href="dashboard.php" class="btn btn-gradient">
                                <i class="fas fa-tachometer-alt"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function printCertificate() {
            var printContents = document.getElementById('certificate').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
</body>
</html>