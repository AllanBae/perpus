<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_ti6b_uas");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil user login dari WordPress
$current_user = wp_get_current_user();
$user_login = $current_user->user_login;

// Query lihat keluhan dan tanggapan
$sql = "SELECT 
            k.idkeluhan AS id_keluhan,
            k.IsiKeluhan,
            t.IsiTanggapan,
            t.TglTanggapan,
            k.TglKeluhan
        FROM tbl_keluhan k
        LEFT JOIN tbl_tanggapan t ON k.idkeluhan = t.idkeluhan
        WHERE k.NIPNIDNNIM = ?
        ORDER BY k.TglKeluhan DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare gagal: " . $conn->error);
}
$stmt->bind_param("s", $user_login);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Tambahkan CSS untuk card panjang -->
<style>
    .wide-card {
        width: 100%;
        min-width: 1000px;
    }
    .scroll-wrapper {
        overflow-x: auto;
    }
</style>

<div class="container-fluid mt-4">
    <h3 class="mb-4">Daftar Keluhan dan Tanggapan</h3>

    <div class="scroll-wrapper">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="card border-primary mb-4 shadow-sm wide-card">
                <div class="card-header bg-primary text-white">
                    <strong>ID Keluhan:</strong> <?= htmlspecialchars($row['id_keluhan']) ?>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        📅 Tanggal Keluhan: <?= date('d-m-Y H:i', strtotime($row['TglKeluhan'])) ?>
                    </small>

                    <h5 class="card-title text-primary">Keluhan</h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($row['IsiKeluhan'])) ?></p>

                    <hr>
                    <h6 class="text-secondary">Tanggapan</h6>
                    <p>
                        <?= $row['IsiTanggapan'] ? nl2br(htmlspecialchars($row['IsiTanggapan'])) : '<em>Belum ada tanggapan.</em>' ?>
                    </p>
                    <?php if ($row['TglTanggapan']) : ?>
                        <small class="text-muted">🕒 Ditanggapi pada: <?= date('d-m-Y H:i', strtotime($row['TglTanggapan'])) ?></small>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
?>
