<?php
if (!isset($conn)) {
    echo "<div class='alert alert-danger'>Koneksi database tidak tersedia.</div>";
    return;
}

// Ambil semua keluhan dari mahasiswa
$sql = "
SELECT 
    k.idkeluhan,
    k.tglkeluhan,
    k.isikeluhan,
    j.nmjenis,
    m.display_name AS nama_mhs
FROM tbl_keluhan k
LEFT JOIN tbl_jenis j ON k.idjenis = j.idjenis
LEFT JOIN wp_users m ON k.nipnidnim = m.user_login
ORDER BY k.idkeluhan DESC
";

$result = $conn->query($sql);
?>

<h3>Daftar Keluhan Mahasiswa</h3>

<?php if ($result && $result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jenis Keluhan</th>
                    <th>Isi Keluhan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['idkeluhan'] ?></td>
                        <td><?= htmlspecialchars($row['nama_mhs']) ?></td>
                        <td><?= htmlspecialchars($row['nmjenis']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['isikeluhan'])) ?></td>
                        <td><?= $row['tglkeluhan'] ?></td>
                        <td>
                            <a href="admin.php?page=utama&panggil=tanggapan.php&idkeluhan=<?= $row['idkeluhan'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-reply"></i> Tanggapi
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Belum ada data keluhan.</div>
<?php endif; ?>
