<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Lokasi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">
    <style>
        /* Gaya CSS seperti yang sudah ada sebelumnya */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #43D681, #ffff4d, #0E9297, #A0FFBF); /* Gradient warna Coral Wave */
            padding: 20px; /* Memberikan padding untuk body */
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Putih dengan transparansi */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #2e7d32;
        }
        #map {
            height: 500px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #2e7d32;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        thead {
            background-color: #81c784;
            color: #ffffff;
        }
        .btn-hapus {
            background-color: #e57373;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            text-decoration: none;
        }
        .btn-hapus:hover {
            background-color: #ef5350;
        }
        .btn-edit {
            background-color: #64b5f6; /* Biru muda */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            text-decoration: none;
        }
        .btn-edit:hover {
            background-color: #42a5f5; /* Biru lebih gelap */
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="alert alert-warning text-center" role="alert">
        <h1>WEBSITE GIS LEAFLETJS DAN PHP</h1>
        <h4>Provinsi Daerah Istimewa Yogyakarta dan Jawa Tengah</h4>
    </div>

    <h2 class="text-center mb-4"></h2>
    <div id="map"></div>

    <h2 class="mt-5">Data Penduduk</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kecamatan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Luas</th>
                <th>Jumlah Penduduk</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Sesuaikan dengan setting MySQL
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "penduduk_db";

            // Membuat koneksi
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Mengecek koneksi
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query untuk mengambil data dari tabel penduduk
            $sql = "SELECT kecamatan, longitude, latitude, luas, jumlah_penduduk FROM penduduk";
            $result = $conn->query($sql);

            // Menyiapkan array untuk marker
            $markers = [];

            if ($result->num_rows > 0) {
                // Loop untuk mengambil data
                while ($row = $result->fetch_assoc()) {
                    $kecamatan = $row['kecamatan'];
                    $longitude = $row['longitude'];
                    $latitude = $row['latitude'];
                    $luas = $row['luas'];
                    $jumlah_penduduk = $row['jumlah_penduduk'];

                    // Simpan data marker ke dalam array
                    $markers[] = [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'kecamatan' => $kecamatan,
                        'luas' => $luas,
                        'jumlah_penduduk' => $jumlah_penduduk
                    ];

                    // Menampilkan data di dalam tabel
                    echo "<tr>
                            <td>" . htmlspecialchars($kecamatan) . "</td>
                            <td>" . htmlspecialchars($longitude) . "</td>
                            <td>" . htmlspecialchars($latitude) . "</td>
                            <td>" . htmlspecialchars($luas) . "</td>
                            <td align='right'>" . htmlspecialchars($jumlah_penduduk) . "</td>
                            <td>
                                <a href='delete.php?kecamatan=" . urlencode($kecamatan) . "' class='btn-hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                                <a href='input.php?kecamatan=" . urlencode($kecamatan) . "&longitude=" . urlencode($longitude) . "&latitude=" . urlencode($latitude) . "&luas=" . urlencode($luas) . "&jumlah_penduduk=" . urlencode($jumlah_penduduk) . "' class='btn-edit'>Edit</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada data untuk ditampilkan.</td></tr>";
            }

            // Menutup koneksi
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
     // Inisialisasi peta dengan koordinat pusat awal
     var map = L.map('map').setView([-7.75, 110.25], 12);

// Definisikan beberapa layer basemap
var baseLayers = {
    "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }),
    "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://www.esri.com/">Esri</a> contributors'
    }),
    "Terrain": L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://opentopomap.org">OpenTopoMap</a> contributors'
    })
};

// Menambahkan basemap default ke peta
baseLayers["OpenStreetMap"].addTo(map);

// Tambahkan kontrol layer ke peta untuk ganti basemap
L.control.layers(baseLayers).addTo(map);

    // Menambahkan marker ke dalam peta
    <?php
    foreach ($markers as $marker) {
        echo "L.marker([{$marker['latitude']}, {$marker['longitude']}]).addTo(map)
            .bindPopup('<b>Kecamatan: {$marker['kecamatan']}</b><br>Luas: {$marker['luas']} km²<br>Jumlah Penduduk: {$marker['jumlah_penduduk']}');\n";
    }
    ?>
</script>

</body>
</html>
