<?php
global $wpdb;

// Hapus user
if (isset($_GET['hapus'])) {
    $idHapus = intval($_GET['hapus']);
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    if (wp_delete_user($idHapus)) {
        echo '<div class="alert alert-success mt-3">User berhasil dihapus!</div>';
    } else {
        echo '<div class="alert alert-danger mt-3">Gagal menghapus user!</div>';
    }
    echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
}

// Edit user
if (isset($_GET['edit'])) {
    $editID = intval($_GET['edit']);
    $edit_user = get_userdata($editID);
    $edit_nohp = $wpdb->get_var($wpdb->prepare("SELECT nohp FROM {$wpdb->users} WHERE ID = %d", $editID));
}

// Simpan user (tambah atau edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'] ?? '';
    $display_name = sanitize_text_field($_POST['display_name']);
    $nohp = sanitize_text_field($_POST['nohp']);
    $peran = "mhs";

    if (!empty($_POST['edit_id'])) {
        // UPDATE
        $edit_id = intval($_POST['edit_id']);

        wp_update_user([
            'ID' => $edit_id,
            'user_email' => $email,
            'display_name' => $display_name
        ]);

        if (!empty($password)) {
            wp_set_password($password, $edit_id); // Ganti password jika diisi
        }

        $wpdb->update(
            $wpdb->users,
            ['nohp' => $nohp, 'peran' => $peran],
            ['ID' => $edit_id]
        );

        update_user_meta($edit_id, 'nohp', $nohp);
        update_user_meta($edit_id, 'peran', $peran);

        echo '<div class="alert alert-success mt-3">Data user berhasil diperbarui.</div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
    } else {
        // INSERT
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('subscriber');

            wp_update_user([
                'ID' => $user_id,
                'display_name' => $display_name
            ]);

            $wpdb->update(
                $wpdb->users,
                ['nohp' => $nohp, 'peran' => $peran],
                ['ID' => $user_id]
            );

            update_user_meta($user_id, 'nohp', $nohp);
            update_user_meta($user_id, 'peran', $peran);

            echo '<div class="alert alert-success mt-3">User baru berhasil dibuat.</div>';
            echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
        } else {
            echo '<div class="alert alert-danger mt-3">Gagal membuat user: ' . $user_id->get_error_message() . '</div>';
        }
    }
}

// Ambil semua user
$users = get_users(['orderby' => 'ID', 'order' => 'ASC']);
?>

<h2><?= isset($edit_user) ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h2>

<form method="POST" class="mb-4">
    <?php if (isset($edit_user)): ?>
        <input type="hidden" name="edit_id" value="<?= $edit_user->ID ?>">
    <?php endif; ?>

    <!-- Nama Lengkap -->
    <div class="mb-3">
        <label>Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
            <input type="text" name="display_name" class="form-control" required
                value="<?= esc_attr($edit_user->display_name ?? '') ?>">
        </div>
    </div>

    <!-- Username -->
    <div class="mb-3">
        <label>Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-user-tag"></i></span>
            <input type="text" name="username" id="username" class="form-control" required
                value="<?= esc_attr($edit_user->user_login ?? '') ?>"
                <?= isset($edit_user) ? 'readonly' : '' ?>>
        </div>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label>Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            <input type="email" name="email" id="email" class="form-control" required
                value="<?= esc_attr($edit_user->user_email ?? '') ?>"
                <?= isset($edit_user) ? '' : 'readonly' ?>>
        </div>
    </div>

    <!-- Password -->
    <?php if (!isset($edit_user)): ?>
        <div class="mb-3">
            <label>Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" id="password" value="kampuskompetensi" required>
                <span class="input-group-text">
                    <i class="fa fa-eye toggle-password" style="cursor:pointer" onclick="togglePassword()"></i>
                </span>
            </div>
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label>Password Baru (opsional)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" id="password">
                <span class="input-group-text">
                    <i class="fa fa-eye toggle-password" style="cursor:pointer" onclick="togglePassword()"></i>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <!-- No HP -->
    <div class="mb-3">
        <label>No. HP</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-phone"></i></span>
            <input type="text" name="nohp" class="form-control" value="<?= esc_attr($edit_nohp ?? '') ?>">
        </div>
    </div>

    <!-- Tombol -->
    <button type="submit" class="btn btn-primary"><?= isset($edit_user) ? 'Update' : 'Simpan' ?></button>
    <a href="admin.php?page=utama&panggil=mahasiswa.php" class="btn btn-secondary">Batal</a>
</form>

<hr>

<h3>Daftar Mahasiswa</h3>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Display Name</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Role WP</th>
            <th>Peran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($users as $user):
            $nohp = $wpdb->get_var($wpdb->prepare("SELECT nohp FROM {$wpdb->users} WHERE ID = %d", $user->ID));
            $peran = $wpdb->get_var($wpdb->prepare("SELECT peran FROM {$wpdb->users} WHERE ID = %d", $user->ID));
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc_html($user->user_login) ?></td>
                <td><?= esc_html($user->display_name) ?></td>
                <td><?= esc_html($user->user_email) ?></td>
                <td><?= esc_html($nohp) ?></td>
                <td><?= implode(', ', $user->roles) ?></td>
                <td><?= esc_html($peran ?: '-') ?></td>
                <td>
                    <a href="admin.php?page=utama&panggil=mahasiswa.php&edit=<?= $user->ID ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="admin.php?page=utama&panggil=mahasiswa.php&hapus=<?= $user->ID ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const icon = document.querySelector(".toggle-password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    // Auto generate email ketika username diisi (saat tambah user)
    document.getElementById('username')?.addEventListener('input', function() {
        const emailInput = document.getElementById('email');
        if (!<?= json_encode(isset($edit_user)) ?>) {
            emailInput.value = this.value + '@atmaluhur.ac.id';
        }
    });
</script>