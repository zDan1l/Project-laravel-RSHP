<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi - RSHP UNAIR</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styling Khusus untuk Halaman Struktur Organisasi */
        .header-struktur {
            text-align: center;
            padding: 60px 0;
            background-color: #f7fff0;
            border-bottom: 4px solid #004d99;
        }

        .header-struktur h1 {
            color: #004d99;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .struktur-section {
            padding: 40px 0;
            text-align: center;
        }
        
        /* Box untuk jabatan/nama */
        .jabatan-box {
            background-color: #004d99; /* Biru UNAIR */
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            display: inline-block;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            min-width: 200px;
        }
        
        .jabatan-box h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .jabatan-box p {
            margin: 5px 0 0 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Styling Garis Hirarki (Sederhana menggunakan CSS) */
        .hirarki-level {
            margin-top: 30px;
            margin-bottom: 50px;
            position: relative;
        }

        .penghubung {
            height: 30px;
            width: 2px;
            background-color: #ccc;
            margin: 0 auto;
        }
        
        .level-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .level-line {
            width: 100%;
            height: 2px;
            background-color: #ccc;
            margin: 10px 0;
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
            <a href="kontak-kami.php">Kontak Kami</a>
        </div>
    </div>

    <main>
        <section class="header-struktur">
            <div class="container">
                <h1>Struktur Organisasi</h1>
                <p>Tatanan kepemimpinan dan manajerial Rumah Sakit Hewan Pendidikan UNAIR.</p>
            </div>
        </section>

        <section class="content struktur-section fade-in">
            <div class="container">
                <h2>Bagan Struktur Kepemimpinan RSHP UNAIR</h2>
                <p style="margin-bottom: 30px; color: #7f8c8d;">Organisasi kami dibangun atas dasar efisiensi dan profesionalitas.</p>

                <div class="hirarki-level">
                    <div class="jabatan-box">
                        <h3>DIREKTUR</h3>
                        <p>Prof. Dr. drh. Nama Direktur, M.Kes.</p>
                    </div>
                </div>

                <div class="penghubung"></div>

                <div class="hirarki-level">
                    <div class="level-group">
                        <div class="jabatan-box" style="background-color: #e67e22;">
                            <h3>WAKIL DIREKTUR PELAYANAN</h3>
                            <p>Drh. Nama Wadir I, Sp.P.V.</p>
                        </div>
                        <div class="jabatan-box" style="background-color: #e67e22;">
                            <h3>WAKIL DIREKTUR PENDIDIKAN & PENELITIAN</h3>
                            <p>Drh. Nama Wadir II, Ph.D.</p>
                        </div>
                        <div class="jabatan-box" style="background-color: #e67e22;">
                            <h3>WAKIL DIREKTUR ADMINISTRASI & KEUANGAN</h3>
                            <p>Nama Wadir III, S.E., M.M.</p>
                        </div>
                    </div>
                </div>

                <div class="penghubung"></div>

                <div class="hirarki-level">
                    <h4 style="color: #2c3e50; margin-bottom: 20px;">DI BAWAH WADIR PELAYANAN</h4>
                    <div class="level-group">
                        <div class="jabatan-box" style="background-color: #27ae60;">
                            <h3>KEPALA INSTALASI RAWAT JALAN</h3>
                            <p>Drh. Nama Kapala</p>
                        </div>
                        <div class="jabatan-box" style="background-color: #27ae60;">
                            <h3>KEPALA INSTALASI RAWAT INAP & INTENSIF</h3>
                            <p>Drh. Nama Kepala</p>
                        </div>
                        <div class="jabatan-box" style="background-color: #27ae60;">
                            <h3>KEPALA INSTALASI BEDAH & ANESTESI</h3>
                            <p>Drh. Nama Kapala</p>
                        </div>
                        <div class="jabatan-box" style="background-color: #27ae60;">
                            <h3>KEPALA UNIT PENUNJANG MEDIS (LAB & RADIOLOGI)</h3>
                            <p>Drh. Nama Kepala</p>
                        </div>
                    </div>
                </div>

                <p style="margin-top: 50px; color: #7f8c8d;">*Bagan di atas adalah representasi sederhana. Struktur organisasi resmi dapat dilihat di kantor RSHP.</p>
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
</body>
</html>