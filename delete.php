<?php
// Mengecek apakah parameter 'kecamatan' ada
if (isset($_GET['kecamatan'])) {
    $kecamatan = $_GET['kecamatan'];

    // Menyesuaikan dengan setting MySQL
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

    // Sebagai Query untuk menghapus data berdasarkan kecamatan
    $sql = "DELETE FROM penduduk WHERE kecamatan = '$kecamatan'";

    // Eksekusi query dan cek hasil
    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil dihapus.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Menutup koneksi
    $conn->close();

    // Redirect kembali ke halaman utama setelah penghapusan
    header("Location: index.php");
    exit;
} else {
    echo "Kecamatan tidak ditemukan.";
}
?>
