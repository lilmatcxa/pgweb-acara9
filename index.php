<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #a0f9cb;
            /* Warna hijau muda */
        }

        /* Style pesan sukses */
        .message {
            background-color: #d4edda;
            /* Hijau muda */
            color: #155724;
            /* Warna hijau tua */
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        /* Style tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #c8e6c9;
            /* Warna hijau terang untuk header tabel */
            color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-link {
            color: #d9534f;
            /* Warna merah untuk link hapus */
            text-decoration: none;
            font-weight: bold;
        }

        .action-link:hover {
            color: #c9302c;
            /* Warna merah lebih gelap saat hover */
            text-decoration: underline;
        }

        .container {
            background-color: #ffffff;
            /* Putih untuk kontainer */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .btn-leaflet {
            background-color: #5cb85c;
            /* Warna hijau untuk tombol */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-leaflet:hover {
            background-color: #4cae4c;
            /* Warna hijau lebih gelap saat hover */
        }
    </style>
</head>

<body>

    <div class="container">
        <?php
        // Tampilkan pesan jika ada
        if (isset($_GET['message'])) {
            echo "<div class='message'>" . htmlspecialchars($_GET['message']) . "</div>";
        }
        ?>

        <!-- Tombol menuju halaman Leaflet -->
        <div class="text-right mb-3">
            <a href="leafletjs.php" class="btn-leaflet">Lihat Peta Lokasi</a>
        </div>

        <?php
        // Sesuaikan dengan setting MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "penduduk_db";

        // Membuat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Cek koneksi
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query untuk mengambil data dari tabel penduduk
        $sql = "SELECT * FROM penduduk";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered'>
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
                <tbody>";

            // Tampilkan data tiap baris
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["kecamatan"]) . "</td>
                    <td>" . htmlspecialchars($row["longitude"]) . "</td>
                    <td>" . htmlspecialchars($row["latitude"]) . "</td>
                    <td>" . htmlspecialchars($row["luas"]) . "</td>
                    <td align='right'>" . htmlspecialchars($row["jumlah_penduduk"]) . "</td>
                    <td>
                        <a href='delete.php?kecamatan=" . urlencode($row["kecamatan"]) . "' class='action-link' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
                        |
                        <a href='input.php?kecamatan=" . urlencode($row["kecamatan"]) . "' class='action-link'>Edit</a>
                    </td>
                  </tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>Tidak ada data yang tersedia.</div>";
        }

        // Menutup koneksi
        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>