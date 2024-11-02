<?php
// Cek apakah form dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kecamatan_old = $_POST['kecamatan_old'] ?? '';
    $kecamatan = $_POST['kecamatan'] ?? '';
    $luas = $_POST['luas'] ?? 0;
    $jumlah_penduduk = $_POST['jumlah_penduduk'] ?? 0;
    $longitude = $_POST['longitude'] ?? 0;
    $latitude = $_POST['latitude'] ?? 0;

    // Sesuaikan dengan setting MySQL
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "penduduk_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!empty($kecamatan_old)) {
        // Update data jika dalam mode Edit
        $sql = "UPDATE penduduk SET kecamatan = ?, luas = ?, jumlah_penduduk = ?, longitude = ?, latitude = ? WHERE kecamatan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdddds", $kecamatan, $luas, $jumlah_penduduk, $longitude, $latitude, $kecamatan_old);
    } else {
        // Insert data baru jika bukan dalam mode Edit
        $sql = "INSERT INTO penduduk (kecamatan, luas, jumlah_penduduk, longitude, latitude) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdddd", $kecamatan, $luas, $jumlah_penduduk, $longitude, $latitude);
    }

    if ($stmt->execute()) {
        header("Location: index.php?message=Data berhasil disimpan!");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    // Mode pengambilan data untuk Edit
    $kecamatan = $_GET['kecamatan'] ?? '';
    $luas = $jumlah_penduduk = $longitude = $latitude = '';

    if (!empty($kecamatan)) {
        // Koneksi database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "penduduk_db";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM penduduk WHERE kecamatan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $kecamatan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $longitude = $data['longitude'];
            $latitude = $data['latitude'];
            $luas = $data['luas'];
            $jumlah_penduduk = $data['jumlah_penduduk'];
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Data Penduduk</title>
</head>
<body>
    <form action="input.php" method="post">
        <input type="hidden" name="kecamatan_old" value="<?php echo htmlspecialchars($kecamatan); ?>">
        
        <label for="kecamatan">Kecamatan:</label><br>
        <input type="text" id="kecamatan" name="kecamatan" value="<?php echo htmlspecialchars($kecamatan); ?>"><br>

        <label for="longitude">Longitude:</label><br>
        <input type="text" id="longitude" name="longitude" value="<?php echo htmlspecialchars($longitude); ?>"><br>

        <label for="latitude">Latitude:</label><br>
        <input type="text" id="latitude" name="latitude" value="<?php echo htmlspecialchars($latitude); ?>"><br>

        <label for="luas">Luas:</label><br>
        <input type="text" id="luas" name="luas" value="<?php echo htmlspecialchars($luas); ?>"><br>

        <label for="jumlah_penduduk">Jumlah Penduduk:</label><br>
        <input type="text" id="jumlah_penduduk" name="jumlah_penduduk" value="<?php echo htmlspecialchars($jumlah_penduduk); ?>"><br><br>

        <input type="submit" value="Simpan">
    </form>
</body>
</html>
