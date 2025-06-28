<?php
if (!isset($conn)) {
    echo "<div class='alert alert-danger'>Koneksi database tidak tersedia.</div>";
    return;
}

$idkeluhan = isset($_GET['idkeluhan']) ? intval($_GET['idkeluhan']) : 0;

if ($idkeluhan === 0) {
    echo "<div class='alert alert-warning'>ID keluhan tidak ditemukan di URL.</div>";
    return;
}

$username_admin = $current_user->user_login ?? 'admin'; // fallback

// Cek apakah keluhan ada
$qKeluhan = $conn->query("SELECT k.*, j.nmjenis 
    FROM tbl_keluhan k 
    LEFT JOIN tbl_jenis j ON k.idjenis = j.idjenis 
    WHERE k.idkeluhan = $idkeluhan");

if (!$qKeluhan || $qKeluhan->num_rows === 0) {
    echo "<div class='alert alert-danger'>Data keluhan tidak ditemukan untuk ID: <strong>$idkeluhan</strong></div>";
    return;
}

$dataKeluhan = $qKeluhan->fetch_assoc();

// Cek apakah sudah ada tanggapan
$qTanggapan = $conn->query("SELECT * FROM tbl_tanggapan WHERE idkeluhan = $idkeluhan");
$dataTanggapan = ($qTanggapan && $qTanggapan->num_rows > 0) ? $qTanggapan->fetch_assoc() : null;

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isitanggapan = $conn->real_escape_string($_POST['isitanggapan']);
    $tgl = date('Y-m-d H:i:s');

    if ($dataTanggapan) {
        // Update jika sudah ada
        $sql = "UPDATE tbl_tanggapan SET 
                isitanggapan = '$isitanggapan',
                tgltanggapan = '$tgl',
                username = '$username_admin'
                WHERE idkeluhan = $idkeluhan";
    } else {
        // Insert baru
        $sql = "INSERT INTO tbl_tanggapan (isitanggapan, idkeluhan, tgltanggapan, username)
                VALUES ('$isitanggapan', $idkeluhan, '$tgl', '$username_admin')";
    }

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success'>Tanggapan berhasil disimpan.</div>";
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=lihtang.php">';
        return;
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan tanggapan: " . $conn->error . "</div>";
    }
}
?>

<h3>Tanggapi Keluhan</h3>
<div class="card mb-3">
    <div class="card-body">
        <p><strong>ID Keluhan:</strong> <?= $dataKeluhan['idkeluhan'] ?></p>
        <p><strong>Jenis:</strong> <?= htmlspecialchars($dataKeluhan['nmjenis']) ?></p>
        <p><strong>Tanggal:</strong> <?= $dataKeluhan['tglkeluhan'] ?></p>
        <p><strong>Isi Keluhan:</strong><br><?= nl2br(htmlspecialchars($dataKeluhan['isikeluhan'])) ?></p>
    </div>
</div>

<form method="POST">
    <div class="mb-3">
        <label for="isitanggapan" class="form-label">Tanggapan Anda</label>
        <textarea name="isitanggapan" id="isitanggapan" rows="5" class="form-control" required><?= $dataTanggapan['isitanggapan'] ?? '' ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim Tanggapan</button>
    <a href="admin.php?page=utama&panggil=lihtang.php" class="btn btn-secondary">Kembali</a>
</form>
