<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Umum - Rumah Sakit Hewan Pendidikan UNAIR</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styling Khusus untuk Halaman Layanan (Jika diperlukan, bisa dipindahkan ke style.css) */
        .service-section {
            padding: 40px 0;
            background-color: #f4f7f6;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .service-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 30px;
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .service-card h3 {
            color: #004d99; /* Biru UNAIR/seperti tombol biru */
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .service-card p {
            color: #555;
            line-height: 1.6;
        }

        .service-card .icon {
            font-size: 3rem;
            color: #ffcc00; /* Kuning UNAIR/seperti tombol kuning */
            margin-bottom: 15px;
            display: inline-block;
        }

        .service-detail-link {
            display: block;
            margin-top: 15px;
            color: #1e90ff; /* Contoh warna link */
            font-weight: bold;
            text-decoration: none;
        }

        .service-detail-link:hover {
            text-decoration: underline;
        }

        .header-layanan {
            text-align: center;
            padding: 60px 0;
            background-color: #e6f0ff; /* Warna latar belakang header halaman */
            border-bottom: 4px solid #004d99;
        }

        .header-layanan h1 {
            color: #004d99;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header-layanan p {
            color: #555;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    
    @include('navbar')


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
        <section class="header-layanan">
            <div class="container">
                <h1>Pelayanan Kesehatan Hewan Komprehensif</h1>
                <p>Kami hadir untuk memberikan solusi kesehatan terbaik bagi hewan kesayangan Anda, didukung oleh tim profesional dan fasilitas modern.</p>
            </div>
        </section>

        <section class="content service-section fade-in">
            <div class="container">
                <h2 style="text-align: center; color: #2c3e50; margin-bottom: 10px;">Layanan Umum RSHP UNAIR</h2>
                <p style="text-align: center; color: #7f8c8d; margin-bottom: 30px;">Berbagai jenis pelayanan medis dan non-medis untuk menjaga kesehatan hewan Anda.</p>
                
                <div class="service-grid">
                    
                    <div class="service-card">
                        <div class="icon">💉</div>
                        <h3>Poli Klinik & Rawat Jalan</h3>
                        <p>Pemeriksaan kesehatan rutin, konsultasi, vaksinasi, dan penanganan kasus penyakit ringan hingga sedang. Tersedia layanan pendaftaran online.</p>
                        <a href="#" class="service-detail-link">Lihat Detail Layanan</a>
                    </div>
                    
                    <div class="service-card">
                        <div class="icon">🔪</div>
                        <h3>Bedah Veteriner</h3>
                        <p>Layanan bedah umum dan spesialis, termasuk bedah ortopedi, soft tissue, dan bedah darurat, didukung ruang operasi steril berstandar.</p>
                        <a href="#" class="service-detail-link">Lihat Detail Layanan</a>
                    </div>

                    <div class="service-card">
                        <div class="icon">🩺</div>
                        <h3>Rawat Inap Intensif (ICU)</h3>
                        <p>Perawatan 24 jam untuk hewan dengan kondisi kritis atau pasca operasi, dipantau ketat oleh dokter jaga dan perawat berpengalaman.</p>
                        <a href="#" class="service-detail-link">Lihat Detail Layanan</a>
                    </div>

                    <div class="service-card">
                        <div class="icon">🐾</div>
                        <h3>Pelayanan Darurat 24 Jam</h3>
                        <p>Tim darurat siap siaga melayani kasus trauma, keracunan, dan kondisi medis mendesak lainnya kapan saja, 7 hari seminggu.</p>
                        <a href="#" class="service-detail-link">Hubungi Darurat Sekarang</a>
                    </div>

                    <div class="service-card">
                        <div class="icon">🦷</div>
                        <h3>Klinik Gigi & Mulut</h3>
                        <p>Perawatan dan pembersihan karang gigi, cabut gigi, dan penanganan penyakit mulut lainnya untuk menjaga kesehatan mulut hewan.</p>
                        <a href="#" class="service-detail-link">Lihat Detail Layanan</a>
                    </div>

                    <div class="service-card">
                        <div class="icon">🛁</div>
                        <h3>Grooming & Pet Care</h3>
                        <p>Layanan perawatan kebersihan (mandi, potong kuku, cukur bulu) yang dilakukan dengan standar higienis dan perhatian penuh.</p>
                        <a href="#" class="service-detail-link">Lihat Detail Layanan</a>
                    </div>

                </div>
                
                <div style="text-align: center; margin-top: 40px;">
                    <a href="register.php" class="btn yellow">PESAN JADWAL ONLINE</a>
                    <a href="tarif-pelayanan.php" class="btn blue">LIHAT TARIF PELAYANAN</a>
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
                        <li><a href="register.php">Pendaftaran Online</a></li>
                        <li><a href="dokter-jaga.php">Jadwal Dokter</a></li>
                        <li><a href="#">Layanan Darurat</a></li>
                        <li><a href="#">Cek Hasil Lab</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Informasi</h4>
                    <ul>
                        <li><a href="tarif-pelayanan.php">Tarif Pelayanan</a></li>
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
</body>
</html>