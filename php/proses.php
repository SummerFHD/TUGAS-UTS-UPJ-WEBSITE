<?php

function saveFormDataToJson($data) {
    $file = '../Data.json';

    // Membuat direktori jika belum ada
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Membuat file JSON jika belum ada
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }

    // Mendapatkan data yang ada dari file JSON
    $existingData = file_get_contents($file);

    // Decode data yang ada ke dalam array
    $existingDataArray = json_decode($existingData, true);

    // Menambahkan data baru ke array yang ada
    $existingDataArray[] = $data;

    // Mengencode array yang telah diperbarui ke format JSON
    $jsonData = json_encode($existingDataArray, JSON_PRETTY_PRINT);

    // Menulis kembali data JSON ke file
    if (file_put_contents($file, $jsonData)) {
        return true; // Data berhasil disimpan
    } else {
        return false; // Data gagal disimpan
    }
}

// Memeriksa apakah formulir telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Memeriksa keberadaan data yang diperlukan
    $requiredFields = ['nama', 'email', 'subject', 'telepon', 'pesan'];
    $formData = [];

    // Memvalidasi dan mengambil data formulir
    foreach ($requiredFields as $field) {
        if (isset($_POST[$field])) {
            $formData[$field] = htmlspecialchars($_POST[$field]); // Melindungi dari serangan XSS
        } else {
            echo "Harap isi semua bidang yang diperlukan.";
            exit;
        }
    }

    // Menyimpan data ke file JSON
    if (saveFormDataToJson($formData)) {
        echo "Data formulir berhasil disimpan.";
    } else {
        echo "Gagal menyimpan data formulir.";
    }
}

// Menampilkan data terakhir yang disimpan
$file = '../Data.json';
$data = json_decode(file_get_contents($file), true);
if ($data) {
    $latestData = end($data); // Mendapatkan data terakhir
    echo "<h2>Data terakhir yang disimpan:</h2>";
    echo "Nama: " . $latestData['nama'] . "<br />";
    echo "Email: " . $latestData['email'] . "<br />";
    echo "Subject: " . $latestData['subject'] . "<br />";
    echo "Telepon: " . $latestData['telepon'] . "<br />";
    echo "Pesan: " . $latestData['pesan'];
} else {
    echo "Belum ada data yang disimpan.";
}
?>
