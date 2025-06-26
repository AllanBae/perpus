<?php
// Ambil data user login dari WordPress
$current_user = wp_get_current_user();
$user_login = $current_user->user_login;
$user_nama = $current_user->display_name;

// Default mode
$MODE = 'tambah';

// Proses insert/update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tglKeluhan = date('Y-m-d H:i:s');
    $idjenis = $_POST['idjenis'];
    $isikeluhan = $_POST['isikeluhan'];
    $nipnidnnim = $user_login;

    // Simpan data ke database
    $query = "INSERT INTO tbl_keluhan (tglkeluhan, isikeluhan, nipnidnnim, idjenis) 
              VALUES ('$tglKeluhan', '$isikeluhan', '$nipnidnnim', '$idjenis')";

    if (mysqli_query($conn, $query)) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        Data berhasil disimpan!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=isikel.php">';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        Gagal menyimpan data! ' . mysqli_error($conn) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        echo '<meta http-equiv="refresh" content="2;url=admin.php?page=utama&panggil=isikel.php">';
    }
}

// Ambil data jenis keluhan
$jenisList = mysqli_query($conn, "SELECT * FROM tbl_jenis");
?>

<h2>Isi Keluhan</h2>
<p>
    Silakan isi keluhan Bapak/Ibu/Sdr/i <b><?= htmlspecialchars($user_nama); ?></b> pada kolom isian yang telah disediakan.
</p>

<form method="POST">
    <div class="mb-3 mt-3">
        <label for="idjenis" class="form-label">Jenis Keluhan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <select name="idjenis" id="idjenis" class="form-select" required>
                <option value="">-- Pilih Jenis Keluhan --</option>
                <?php while ($jenis = mysqli_fetch_assoc($jenisList)) : ?>
                    <option value="<?= $jenis['idjenis'] ?>"><?= htmlspecialchars($jenis['nmjenis']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="isikeluhan" class="form-label">Isi Keluhan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
            <textarea class="form-control" id="isikeluhan" name="isikeluhan" rows="4"
                placeholder="Masukkan isi keluhan minimal 20 karakter dan maksimal 500 karakter"
                required></textarea>
        </div>
        <div class="form-text d-flex justify-content-between">
            <span id="charCount">0 / 500</span>
            <span id="charWarning" class="text-danger" style="display: none;">Isi keluhan harus 20–500 karakter.</span>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Keluhan
        </button>
        <a href="admin.php?page=utama&panggil=bagian.php" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Batal
        </a>
    </div>
</form>

<script>
    const textarea = document.getElementById("isikeluhan");
    const charCount = document.getElementById("charCount");
    const charWarning = document.getElementById("charWarning");
    const form = document.querySelector("form");

    textarea.addEventListener("input", () => {
        const length = textarea.value.length;
        charCount.textContent = ${length} / 500;

        if (length < 20 || length > 500) {
            charWarning.style.display = "inline";
            textarea.classList.add("is-invalid");
        } else {
            charWarning.style.display = "none";
            textarea.classList.remove("is-invalid");
        }
    });

    form.addEventListener("submit", function(e) {
        const length = textarea.value.length;
        if (length < 20 || length > 500) {
            e.preventDefault(); // Batalkan submit
            charWarning.style.display = "inline";
            textarea.classList.add("is-invalid");
            textarea.focus();
        }
    });
</script>