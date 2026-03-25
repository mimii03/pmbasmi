<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($user['status_pendaftaran'] != 'menunggu_ujian') {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $questions = mysqli_query($conn, "SELECT * FROM questions");
    $score = 0;
    $total = mysqli_num_rows($questions);
    
    // Hapus jawaban lama
    mysqli_query($conn, "DELETE FROM user_answers WHERE user_id = $user_id");
    
    while ($q = mysqli_fetch_assoc($questions)) {
        $jawaban = isset($_POST['q_' . $q['id']]) ? $_POST['q_' . $q['id']] : '';
        $is_correct = ($jawaban == $q['jawaban_benar']) ? 1 : 0;
        
        if ($is_correct) $score++;
        
        $query = "INSERT INTO user_answers (user_id, question_id, jawaban, is_correct) 
                  VALUES ($user_id, {$q['id']}, '$jawaban', $is_correct)";
        mysqli_query($conn, $query);
    }
    
    $nilai = ($score / $total) * 100;
    $status = ($nilai >= 70) ? 'lulus' : 'tidak_lulus';
    $status_pendaftaran = ($nilai >= 70) ? 'lulus' : 'tidak_lulus';
    
    $update = "UPDATE users SET status_ujian = 'selesai', nilai = $nilai, 
                status_pendaftaran = '$status_pendaftaran' WHERE id = $user_id";
    mysqli_query($conn, $update);
    
    header("Location: hasil_ujian.php");
    exit();
}

$questions = mysqli_query($conn, "SELECT * FROM questions");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Seleksi - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .timer-container {
            position: fixed;
            top: 80px;
            right: 20px;
            background: white;
            border-radius: 50px;
            padding: 15px 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .timer {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .question-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .option-label {
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 10px;
            transition: all 0.3s;
            display: block;
            margin-bottom: 10px;
            border: 1px solid #e0e0e0;
        }
        
        .option-label:hover {
            background: #f0f0f0;
            border-color: #667eea;
        }
        
        input[type="radio"] {
            margin-right: 10px;
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
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-graduation-cap"></i> Ujian Seleksi
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">Kembali ke Dashboard</a>
            </div>
        </div>
    </nav>
    
    <div class="timer-container">
        <i class="fas fa-clock"></i> 
        <span class="timer" id="timer">30:00</span>
    </div>
    
    <div class="container mt-5 pt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Ujian Seleksi Akademik</h3>
                        <p>Jawablah pertanyaan berikut dengan benar. Waktu pengerjaan 30 menit.</p>
                        <hr>
                        <form method="POST" id="examForm">
                            <?php 
                            $no = 1;
                            while($q = mysqli_fetch_assoc($questions)): 
                            ?>
                            <div class="question-card">
                                <h5><?php echo $no++ . ". " . $q['soal']; ?></h5>
                                <div class="mt-3">
                                    <label class="option-label">
                                        <input type="radio" name="q_<?php echo $q['id']; ?>" value="A" required>
                                        A. <?php echo $q['opsi_a']; ?>
                                    </label>
                                    <label class="option-label">
                                        <input type="radio" name="q_<?php echo $q['id']; ?>" value="B">
                                        B. <?php echo $q['opsi_b']; ?>
                                    </label>
                                    <label class="option-label">
                                        <input type="radio" name="q_<?php echo $q['id']; ?>" value="C">
                                        C. <?php echo $q['opsi_c']; ?>
                                    </label>
                                    <label class="option-label">
                                        <input type="radio" name="q_<?php echo $q['id']; ?>" value="D">
                                        D. <?php echo $q['opsi_d']; ?>
                                    </label>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-gradient btn-lg" onclick="return confirm('Yakin ingin mengumpulkan ujian?')">
                                    <i class="fas fa-paper-plane"></i> Submit Ujian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let timeLeft = 1800; // 30 menit dalam detik
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                document.getElementById('examForm').submit();
            }
            timeLeft--;
        }
        
        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html>