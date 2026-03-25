<!-- footer.php -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="footer-logo">
                        <i class="fas fa-graduation-cap"></i> PRISMA SPMB
                    </div>
                    <p style="color: #aaa;">Sistem Penerimaan Mahasiswa Baru Universitas PRISMA. Proses seleksi online yang transparan dan akuntabel.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Menu</h6>
                    <div class="footer-links">
                        <p><a href="index.php">Beranda</a></p>
                        <p><a href="index.php#about">Tentang</a></p>
                        <p><a href="index.php#jalur">Pendaftaran</a></p>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <p><a href="dashboard.php">Dashboard</a></p>
                        <?php else: ?>
                            <p><a href="login.php">Login</a></p>
                            <p><a href="register.php">Register</a></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Kontak</h6>
                    <div class="footer-links">
                        <p><i class="fas fa-map-marker-alt"></i> Jl. Teknologi No. 123, Jakarta</p>
                        <p><i class="fas fa-phone"></i> (021) 1234-5678</p>
                        <p><i class="fas fa-envelope"></i> info@prisma.ac.id</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Jam Operasional</h6>
                    <div class="footer-links">
                        <p>Senin - Jumat: 08.00 - 17.00</p>
                        <p>Sabtu: 08.00 - 12.00</p>
                        <p>Minggu: Tutup</p>
                    </div>
                </div>
            </div>
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> Universitas PRISMA. All rights reserved.
            </div>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>