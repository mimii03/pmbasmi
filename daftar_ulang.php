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

if ($user['status_pendaftaran'] != 'lulus') {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Buat folder uploads jika belum ada
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (isset($_POST['cropped_image']) && !empty($_POST['cropped_image'])) {
        // Decode base64 image
        $image_data = $_POST['cropped_image'];
        $image_data = str_replace('data:image/png;base64,', '', $image_data);
        $image_data = str_replace(' ', '+', $image_data);
        $image_data = base64_decode($image_data);
        
        $filename = time() . '_' . $user_id . '.png';
        $target_file = $target_dir . $filename;
        
        if (file_put_contents($target_file, $image_data)) {
            $foto = $target_file;
        } else {
            $error = "Gagal menyimpan foto";
        }
    } else {
        $error = "Silakan upload dan crop foto terlebih dahulu";
    }
    
    $setuju = isset($_POST['setuju']) ? 1 : 0;
    
    if (!isset($error) && $setuju && isset($foto)) {
        // Generate NIM
        $year = date('Y');
        $nim = "SGA" . $year . str_pad($user_id, 4, '0', STR_PAD_LEFT);
        
        $update = "UPDATE users SET 
                    foto = '$foto',
                    nim = '$nim',
                    status_pendaftaran = 'aktif'
                  WHERE id = $user_id";
        
        if (mysqli_query($conn, $update)) {
            header('Location: status_mahasiswa.php');
            exit();
        } else {
            $error = "Gagal melakukan daftar ulang: " . mysqli_error($conn);
        }
    } elseif (!isset($error) && !$setuju) {
        $error = "Anda harus menyetujui persyaratan";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang - PMB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        /* Style untuk cropper */
        .img-container {
            max-width: 100%;
            max-height: 400px;
            margin: 0 auto;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
        }
        
        #image {
            max-width: 100%;
            display: block;
        }
        
        .preview-container {
            text-align: center;
            margin-top: 20px;
        }
        
        .preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            border: 3px solid #667eea;
            background: #f0f0f0;
        }
        
        .preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .crop-buttons {
            margin-top: 15px;
            text-align: center;
        }
        
        .btn-sm {
            padding: 5px 15px;
            margin: 0 5px;
        }
        
        .aspect-buttons {
            margin: 15px 0;
            text-align: center;
        }
        
        .aspect-btn {
            margin: 0 5px;
        }
        
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="text-center mb-4">Daftar Ulang</h2>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-box">
                        <h5>Data Pendaftaran Anda</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Lengkap:</strong> <?php echo $user['nama_lengkap']; ?></p>
                                <p><strong>NISN:</strong> <?php echo $user['nisn']; ?></p>
                                <p><strong>Jurusan:</strong> <?php echo $user['jurusan']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nomor Tes:</strong> <?php echo $user['nomor_tes']; ?></p>
                                <p><strong>Asal Sekolah:</strong> <?php echo $user['asal_sekolah']; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" id="uploadForm">
                        <div class="mb-3">
                            <label class="form-label">Upload Foto (3x4 / 4x6)</label>
                            <input type="file" class="form-control" id="photoInput" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Upload foto Anda. Foto akan dipotong sesuai ukuran yang diinginkan</small>
                            
                            <!-- Preview sebelum crop -->
                            <div class="preview-container mt-3" id="previewContainer" style="display: none;">
                                <div class="preview">
                                    <img id="previewImg" src="#" alt="Preview">
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#cropModal">
                                    <i class="fas fa-crop"></i> Sesuaikan Foto
                                </button>
                            </div>
                            
                            <input type="hidden" name="cropped_image" id="croppedImage">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="setuju" id="setuju" required>
                                <label class="form-check-label" for="setuju">
                                    Saya menyetujui seluruh persyaratan dan ketentuan yang berlaku
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-gradient btn-lg" id="submitBtn">
                                <i class="fas fa-check-circle"></i> Konfirmasi Daftar Ulang
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary btn-lg ms-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Crop -->
    <div class="modal fade" id="cropModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sesuaikan Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="aspect-buttons">
                        <button type="button" class="btn btn-outline-primary aspect-btn" data-aspect="3/4">3:4 (Pas foto)</button>
                        <button type="button" class="btn btn-outline-primary aspect-btn" data-aspect="4/6">4:6 (Ukuran biasa)</button>
                        <button type="button" class="btn btn-outline-primary aspect-btn" data-aspect="9/16">9:16 (Full)</button>
                        <button type="button" class="btn btn-outline-primary aspect-btn" data-aspect="1/1">1:1 (Kotak)</button>
                        <button type="button" class="btn btn-outline-primary aspect-btn" data-aspect="free">Bebas</button>
                    </div>
                    <div class="img-container">
                        <img id="cropImage" src="#" alt="Image to crop">
                    </div>
                    <div class="crop-buttons mt-3">
                        <button type="button" class="btn btn-secondary" id="rotateLeft"><i class="fas fa-undo"></i> Putar Kiri</button>
                        <button type="button" class="btn btn-secondary" id="rotateRight"><i class="fas fa-redo"></i> Putar Kanan</button>
                        <button type="button" class="btn btn-secondary" id="zoomIn"><i class="fas fa-search-plus"></i> Zoom In</button>
                        <button type="button" class="btn btn-secondary" id="zoomOut"><i class="fas fa-search-minus"></i> Zoom Out</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropBtn">Simpan & Gunakan</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    
    <script>
        let cropper;
        let currentImage = null;
        
        // Handle file input change
        $('#photoInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentImage = e.target.result;
                    $('#previewImg').attr('src', currentImage);
                    $('#previewContainer').show();
                    $('#cropImage').attr('src', currentImage);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Initialize cropper when modal opens
        $('#cropModal').on('shown.bs.modal', function() {
            if (currentImage) {
                if (cropper) {
                    cropper.destroy();
                }
                const image = document.getElementById('cropImage');
                cropper = new Cropper(image, {
                    aspectRatio: 3/4,
                    viewMode: 2,
                    dragMode: 'move',
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    background: true,
                    autoCropArea: 0.8,
                    guides: true,
                    center: true,
                    highlight: true,
                });
            }
        });
        
        // Aspect ratio buttons
        $('.aspect-btn').on('click', function() {
            const aspect = $(this).data('aspect');
            if (aspect === 'free') {
                cropper.setAspectRatio(NaN);
            } else if (aspect === '3/4') {
                cropper.setAspectRatio(3/4);
            } else if (aspect === '4/6') {
                cropper.setAspectRatio(4/6);
            } else if (aspect === '9/16') {
                cropper.setAspectRatio(9/16);
            } else if (aspect === '1/1') {
                cropper.setAspectRatio(1/1);
            }
            
            // Update active button style
            $('.aspect-btn').removeClass('active');
            $(this).addClass('active');
        });
        
        // Rotate buttons
        $('#rotateLeft').on('click', function() {
            cropper.rotate(-90);
        });
        
        $('#rotateRight').on('click', function() {
            cropper.rotate(90);
        });
        
        // Zoom buttons
        $('#zoomIn').on('click', function() {
            cropper.zoom(0.1);
        });
        
        $('#zoomOut').on('click', function() {
            cropper.zoom(-0.1);
        });
        
        // Crop and save
        $('#cropBtn').on('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                
                const croppedImage = canvas.toDataURL('image/png');
                $('#previewImg').attr('src', croppedImage);
                $('#croppedImage').val(croppedImage);
                
                // Close modal
                $('#cropModal').modal('hide');
                
                // Update preview style
                $('#previewContainer .preview').css({
                    'width': '150px',
                    'height': '150px',
                    'border-radius': '50%',
                    'overflow': 'hidden'
                });
                
                // Show success message
                const successAlert = `
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle"></i> Foto berhasil disesuaikan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.form-container').prepend(successAlert);
                setTimeout(() => $('.alert-success').fadeOut(), 3000);
            }
        });
        
        // Validate before submit
        $('#uploadForm').on('submit', function(e) {
            if (!$('#croppedImage').val()) {
                e.preventDefault();
                alert('Silakan upload dan sesuaikan foto terlebih dahulu!');
                return false;
            }
            if (!$('#setuju').is(':checked')) {
                e.preventDefault();
                alert('Anda harus menyetujui persyaratan!');
                return false;
            }
        });
        
        // Clean up cropper when modal closes
        $('#cropModal').on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });
    </script>
</body>
</html>