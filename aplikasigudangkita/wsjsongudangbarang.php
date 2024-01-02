<?php
include('koneksi.db.php');

$sql = "SELECT b.KodeBarang, b.NamaBarang, t.WaktuTransaksi, t.StatusTransaksi, t.Jumlah, g.Alamat
        FROM barang b
        INNER JOIN barangdigudang t ON b.KodeBarang = t.KodeBarang
        INNER JOIN gudang g ON t.KodeGudang = g.KodeGudang
        ORDER BY b.KodeBarang";

// Gunakan prepared statement
$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $KodeBarang, $NamaBarang, $WaktuTransaksi, $StatusTransaksi, $Jumlah, $Alamat);

    $algo = "AES-256-CBC";
    $option =0;
    $kunci = "tes";
    $iv = "1234567890112233";

    $arrahhasil = array();

    $enkripsi1 = openssl_encrypt($KodeBarang,$algo,$kunci,$option,$iv);
    $enkripsi2 = openssl_encrypt($NamaBarang,$algo,$kunci,$option,$iv);
    $enkripsi3 = openssl_encrypt($WaktuTransaksi,$algo,$kunci,$option,$iv);
    $enkripsi4 = openssl_encrypt($StatusTransaksi,$algo,$kunci,$option,$iv);
    $enkripsi5 = openssl_encrypt($Jumlah,$algo,$kunci,$option,$iv);
    $enkripsi6 = openssl_encrypt($Alamat,$algo,$kunci,$option,$iv);

    while (mysqli_stmt_fetch($stmt)) {
        $h = array();
        $h['KodeBarang'] = enkripaes($enkripsi1,$KodeBarang, $algo, $kunci, $iv);
        $h['NamaBarang'] = enkripaes($enkripsi2,$NamaBarang, $algo, $kunci, $iv);
        $h['WaktuTransaksi'] = enkripaes($enkripsi3,$WaktuTransaksi, $algo, $kunci, $iv);
        $h['StatusTransaksi'] = enkripaes($enkripsi4,$StatusTransaksi, $algo, $kunci, $iv);
        $h['Jumlah'] = enkripaes($enkripsi5,$Jumlah, $algo, $kunci, $iv);
        $h['Alamat'] = enkripaes($enkripsi6,$Alamat, $algo, $kunci, $iv);
        array_push($arrahhasil, $h);
    }

    mysqli_stmt_close($stmt);
    echo json_encode($arrahhasil);
} else {
    echo "Gagal mengeksekusi query.";
}

function enkripaes($data, $algo, $kunci, $iv) {
    // Implementasi fungsi enkripsi sesuai kebutuhan Anda
    // ...

    return $data;
}
?>
