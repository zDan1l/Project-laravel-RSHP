<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Sakit Hewan Pendidikan UNAIR</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    @include('navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade-in" role="alert">
            <div class="container">
                <div class="alert-content">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="alert-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade-in" role="alert">
            <div class="container">
                <div class="alert-content">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="alert-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade-in" role="alert">
            <div class="container">
                <div class="alert-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                    <button type="button" class="alert-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade-in" role="alert">
            <div class="container">
                <div class="alert-content">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ session('info') }}</span>
                    <button type="button" class="alert-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif


    <header>
        <div class="container">
            <img src="./img/logo-unair.webp" alt="UNAIR Logo" class="logo unair">
        </div>
    </header>

    <div class="sub-nav">
        <div class="container">
            <a href="#">UNAIR</a>
            <a href="#">FKH UNAIR</a>
            <a href="#">Cyber Campus</a>
            <a href="#">Kontak Kami</a>
        </div>
    </div>

    <main>
        <section class="content fade-in">
            <div class="container">
                <div class="content-wrapper-yt">
                    <div class="content-text">
                        <a type="button" href="register.php" class="btn yellow">PENDAFTARAN ONLINE</a>
                        <p>Rumah Sakit Hewan Pendidikan Universitas Airlangga berinovasi untuk selalu meningkatkan kualitas pelayanan. Kami menyediakan fitur pendaftaran online yang memudahkan Anda mendaftarkan hewan kesayangan untuk mendapatkan pelayanan kesehatan terbaik dari tim dokter hewan profesional kami.</p>
                        <br>
                        <p>Dengan teknologi terkini dan fasilitas modern, kami berkomitmen memberikan pelayanan kesehatan hewan yang komprehensif, mulai dari pemeriksaan rutin hingga bedah khusus.</p>
                    </div>
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/rCfvZPECZvE" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                <a href="#" class="btn blue">INFORMASI JADWAL DOKTER JAGA</a>
            </div>
        </section>
        <section class="about-section fade-in">
            <div class="container">
                <h2 style="text-align: center; color: #2c3e50; margin-bottom: 1rem;">Tentang RSHP UNAIR</h2>
                <p style="text-align: center; color: #7f8c8d; margin-bottom: 2rem;">Rumah Sakit Hewan Pendidikan terdepan di Indonesia dengan standar internasional</p>
                
                <div class="about-grid">
                    <div class="about-card">
                        <div class="icon">🏥</div>
                        <h3>Fasilitas Modern</h3>
                        <p>Dilengkapi dengan peralatan medis terkini dan ruang operasi berstandar internasional untuk memberikan pelayanan terbaik bagi hewan kesayangan Anda.</p>
                    </div>
                    <div class="about-card">
                        <div class="icon">👨‍⚕️</div>
                        <h3>Tim Profesional</h3>
                        <p>Dokter hewan berpengalaman dan terlatih yang siap menangani berbagai kasus medis dengan pendekatan yang komprehensif dan penuh kasih sayang.</p>
                    </div>
                    <div class="about-card">
                        <div class="icon">🎓</div>
                        <h3>Pusat Pendidikan</h3>
                        <p>Sebagai rumah sakit pendidikan, kami juga berperan dalam mendidik generasi dokter hewan masa depan dengan standar pendidikan berkualitas tinggi.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats-section">
            <div class="container">
                <h2 style="text-align: center; margin-bottom: 2rem;">Statistik RSHP UNAIR</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <h3>15,000+</h3>
                        <p>Hewan yang telah dilayani</p>
                    </div>
                    <div class="stat-item">
                        <h3>25+</h3>
                        <p>Dokter hewan profesional</p>
                    </div>
                    <div class="stat-item">
                        <h3>10</h3>
                        <p>Tahun pengalaman</p>
                    </div>
                    <div class="stat-item">
                        <h3>24/7</h3>
                        <p>Layanan darurat</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="news fade-in">
            <div class="container">
                <h2>BERITA TERKINI</h2>
                <h3>RSHP Latest News</h3>
                <div class="news-grid">
                    <div class="news-item">
                        <img src="./img/news-1.png" alt="News 1">
                        <div class="news-content">
                            <div class="news-date">15 Agustus 2025</div>
                            <h4 class="news-title">Pembukaan Layanan Baru: Unit Kardiologi Hewan</h4>
                            <p>RSHP UNAIR dengan bangga memperkenalkan unit kardiologi hewan terbaru dengan peralatan canggih untuk menangani penyakit jantung pada hewan.</p>
                        </div>
                    </div>
                    <div class="news-item">
                        <img src="./img/news-2.jpg" alt="News 2">
                        <div class="news-content">
                            <div class="news-date">12 Agustus 2025</div>
                            <h4 class="news-title">Workshop Kesehatan Hewan untuk Masyarakat</h4>
                            <p>Kegiatan edukasi gratis tentang cara merawat hewan peliharaan yang sehat dan pencegahan penyakit menular.</p>
                        </div>
                    </div>
                    <div class="news-item">
                        <img src="./img/news-3.webp" alt="News 3">
                        <div class="news-content">
                            <div class="news-date">10 Agustus 2025</div>
                            <h4 class="news-title">Keberhasilan Operasi Langka pada Kucing Persia</h4>
                            <p>Tim dokter RSHP berhasil melakukan operasi kompleks untuk menyelamatkan kucing Persia dengan kondisi medis yang langka.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Kontak Kami</h4>
                    <p>📍 Kampus C UNAIR, Mulyorejo</p>
                    <p>📞 (031) 5992785</p>
                    <p>✉️ rshp@unair.ac.id</p>
                    <p>🕒 Senin - Jumat: 08.00 - 17.00</p>
                </div>
                <div class="footer-section">
                    <h4>Layanan Cepat</h4>
                    <ul>
                        <li><a href="#">Pendaftaran Online</a></li>
                        <li><a href="#">Jadwal Dokter</a></li>
                        <li><a href="#">Layanan Darurat</a></li>
                        <li><a href="#">Cek Hasil Lab</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Informasi</h4>
                    <ul>
                        <li><a href="#">Tarif Pelayanan</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Ikuti Kami</h4>
                    <p>Dapatkan update terbaru tentang layanan dan informasi kesehatan hewan</p>
                    <p>📘 Facebook: RSHP UNAIR</p>
                    <p>📷 Instagram: @rshp_unair</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Rumah Sakit Hewan Pendidikan UNAIR. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Auto dismiss flash messages after 5 seconds --}}
    <script>
        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s, transform 0.5s';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>