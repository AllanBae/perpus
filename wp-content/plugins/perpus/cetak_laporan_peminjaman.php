<?php
global $conn;

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';

if (!$tgl_mulai || !$tgl_selesai) {
    echo "<div class='alert alert-danger text-center'>Silakan pilih tanggal terlebih dahulu.</div>";
    exit;
}

$query = "
    SELECT 
        pj.no_peminjaman,
        pj.tgl_peminjaman,
        a.id_anggota,
        a.nm_anggota,
        b.id_buku,
        b.judul_buku,
        GROUP_CONCAT(d.no_copy_buku SEPARATOR ', ') AS no_copy,
        COUNT(d.no_copy_buku) AS jumlah
    FROM peminjaman pj
    LEFT JOIN anggota a ON pj.id_anggota = a.id_anggota
    LEFT JOIN dapat d ON pj.no_peminjaman = d.no_peminjaman
    LEFT JOIN copy_buku cb ON d.no_copy_buku = cb.no_copy_buku
    LEFT JOIN buku b ON cb.id_buku = b.id_buku
    WHERE pj.tgl_peminjaman BETWEEN '$tgl_mulai' AND '$tgl_selesai'
    GROUP BY pj.no_peminjaman, b.id_buku
    ORDER BY pj.tgl_peminjaman ASC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial;
            font-size: 14px;
            margin: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 0;
        }
        p {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #555;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        @media print {
            nav.navbar, #adminmenumain, #adminmenuwrap, #adminmenuback, #wpadminbar, #wpfooter,
            .update-nag, .notice, .error, .updated, .wrap > h1, .wrap > .nav-tab-wrapper,
            .wrap > .notice, .wrap > .error, .wrap > .updated, .wrap > .wp-header-end,
            .wrap > form, .wrap > div:not(.cetak-laporan-container), .wrap > *:not(.cetak-laporan-container),
            .wrap > br {
                display: none !important;
            }
            html, body {
                margin: 0;
                padding: 0;
                background: #fff;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <h2>LAPORAN PEMINJAMAN BUKU</h2>
    <p>Periode: <?= date('d-m-Y', strtotime($tgl_mulai)) ?> s/d <?= date('d-m-Y', strtotime($tgl_selesai)) ?></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Peminjaman</th>
                <th>Tanggal Pinjam</th>
                <th>ID Anggota</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['no_peminjaman'] ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tgl_peminjaman'])) ?></td>
                        <td><?= $row['id_anggota'] ?></td>
                        <td><?= $row['nm_anggota'] ?></td>
                        <td><?= $row['judul_buku'] ?></td>
                        <td><?= $row['jumlah'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada data peminjaman dalam periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>