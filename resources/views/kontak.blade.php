<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Rumah Sakit Hewan Pendidikan UNAIR</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styling Khusus untuk Halaman Kontak */
        .header-kontak {
            text-align: center;
            padding: 60px 0;
            background-color: #f0f8ff;
            border-bottom: 4px solid #004d99;
        }

        .header-kontak h1 {
            color: #004d99;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .kontak-section {
            padding: 40px 0;
        }

        .kontak-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 30px;
        }

        .kontak-info h2, .form-wrapper h2 {
            color: #2c3e50;
            border-bottom: 2px solid #ffcc00;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kontak-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .kontak-item .icon {
            font-size: 1.8rem;
            color: #004d99;
            margin-right: 15px;
        }

        .map-container {
            height: 400px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 30px;
        }

        /* Styling Formulir Kontak Sederhana */
        .form-wrapper {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .form-wrapper label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-wrapper input[type="text"],
        .form-wrapper input[type="email"],
        .form-wrapper textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-wrapper textarea {
            resize: vertical;
            height: 120px;
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
            <a href="kontak-kami.php" class="active">Kontak Kami</a> </div>
    </div>

    <main>
        <section class="header-kontak">
            <div class="container">
                <h1>Kontak Kami</h1>
                <p>Silakan hubungi kami melalui informasi di bawah ini atau kirim pesan Anda langsung.</p>
            </div>
        </section>

        <section class="content kontak-section fade-in">
            <div class="container">
                <div class="kontak-grid">
                    <div class="kontak-info">
                        <h2>Informasi Detail</h2>
                        
                        <div class="kontak-item">
                            <div class="icon">📍</div>
                            <div>
                                <strong>Alamat Utama</strong>
                                <p>Kampus C UNAIR, Mulyorejo, Surabaya, Jawa Timur 60115</p>
                            </div>
                        </div>
                        
                        <div class="kontak-item">
                            <div class="icon">📞</div>
                            <div>
                                <strong>Telepon Utama</strong>
                                <p>(031) 5992785 (Jam Kerja)</p>
                                <p>(031) 599xxxx (Layanan Darurat 24 Jam)</p>
                            </div>
                        </div>
                        
                        <div class="kontak-item">
                            <div class="icon">✉️</div>
                            <div>
                                <strong>Email Resmi</strong>
                                <p>rshp@unair.ac.id</p>
                            </div>
                        </div>
                        
                        <div class="kontak-item">
                            <div class="icon">🕒</div>
                            <div>
                                <strong>Jam Operasional Rawat Jalan</strong>
                                <p>Senin - Jumat: 08.00 - 17.00 WIB</p>
                                <p>Layanan Darurat: 24 Jam Non-Stop</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-wrapper">
                        <h2>Kirimkan Pesan Anda</h2>
                        <form action="submit_kontak.php" method="POST">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required>

                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                            
                            <label for="subjek">Subjek Pesan</label>
                            <input type="text" id="subjek" name="subjek" required>

                            <label for="pesan">Isi Pesan</label>
                            <textarea id="pesan" name="pesan" required></textarea>

                            <button type="submit" class="btn yellow" style="width: 100%;">Kirim Pesan</button>
                        </form>
                    </div>
                </div>

                <h2 style="text-align: center; margin-top: 40px; color: #2c3e50;">Peta Lokasi</h2>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.514436585149!2d112.78457631477484!3d-7.291702994727821!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa33d5964993%3A0x8898b584a22b0704!2sRumah%20Sakit%20Hewan%20Pendidikan%20Universitas%20Airlangga!5e0!3m2!1sid!2sid!4v1634358872233!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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