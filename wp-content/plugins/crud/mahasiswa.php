<?php
// Handle hapus user
if (isset($_GET['hapus'])) {
    $idHapus = intval($_GET['hapus']);
    require_once(ABSPATH . 'wp-admin/includes/user.php'); // penting untuk delete_user
    if (wp_delete_user($idHapus)) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            User berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            Gagal menghapus user!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
}
$edit_user = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $edit_user_id = intval($_GET['id']);
    $edit_user = get_userdata($edit_user_id);
}

// Proses insert user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $display_name = sanitize_text_field($_POST['display_name']);
    $peran = "mhs";

    if ($edit_id > 0) {
        // UPDATE USER
        $userdata = [
            'ID' => $edit_id,
            'user_email' => $email,
            'display_name' => $display_name
        ];
        if (!empty($password)) {
            $userdata['user_pass'] = $password;
        }

        $result = wp_update_user($userdata);

        if (!is_wp_error($result)) {
            update_user_meta($edit_id, 'peran', $peran);
            echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                Data mahasiswa berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Gagal memperbarui data: ' . $result->get_error_message() . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    } else {
        // INSERT USER BARU
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            $user = new WP_User($user_id);
            $user->set_role('subscriber');
            wp_update_user([
                'ID' => $user_id,
                'display_name' => $display_name
            ]);
            update_user_meta($user_id, 'peran', $peran);
            echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                Berhasil membuat user baru: ' . esc_html($display_name) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Gagal membuat user: ' . $user_id->get_error_message() . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}

// Ambil user WordPress
$users = get_users([
    'orderby' => 'ID',
    'order'   => 'ASC'
]); ?>

<h2 class="text-center">Manajemen Mahasiswa</h2>
<form method="POST" class="mb-4">
    <input type="hidden" name="edit_id" value="<?php echo $edit_user ? $edit_user->ID : ''; ?>">

    <div class="mb-3">
        <label class="form-label">Nama Lengkap (Display Name)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" name="display_name" class="form-control" placeholder="Nama Lengkap"
                value="<?php echo $edit_user ? esc_attr($edit_user->display_name) : ''; ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Username"
                value="<?php echo $edit_user ? esc_attr($edit_user->user_login) : ''; ?>"
                >
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Alamat Email"
                value="<?php echo $edit_user ? esc_attr($edit_user->user_email) : ''; ?>" required
                <?php echo $edit_user ? 'readonly' : 'required'; ?>>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Website</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-globe"></i></span>
            <input type="url" name="website" class="form-control" placeholder="https://">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password <?php if ($edit_user) echo "(Kosongkan jika tidak diubah)"; ?></label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
    </div>

    <?php if ($edit_user): ?>
    <div class="text-center mt-3">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Simpan
    </button>
    <a href="admin.php?page=utama&panggil=mahasiswa.php" class="btn btn-secondary ms-2">
        <i class="fas fa-times"></i> Batal
    </a>
</div>
    <?php endif; ?>

</form>

<h3>Daftar Mahasiswa</h3>
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Id</th>
            <th>Username</th>
            <th>Display Name</th>
            <th>Email</th>
            <th>Role WP</th>
            <th>Peran (Custom)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $peran_mapping = [
            'mhs' => 'Mahasiswa',
            'dsn' => 'Dosen',
            'kry' => 'Karyawan'
        ];

        $no = 1;
        foreach ($users as $user):
            $peran_code = $user->peran;
            $peran_label = isset($peran_mapping[$peran_code]) ? $peran_mapping[$peran_code] : '-';
            echo "<tr>
        <td>{$no}</td>
         <td>{$user->ID}</td>
        <td>{$user->user_login}</td>
        <td>{$user->display_name}</td>
        <td>{$user->user_email}</td>
        <td>" . implode(', ', $user->roles) . "</td>
        <td>" . esc_html($peran_label) . "</td>
        <td>

            <a href='admin.php?page=utama&panggil=mahasiswa.php&id={$user->ID}' class='btn btn-warning btn-sm ms-1'>
                 <i class='fas fa-edit'></i> Ubah
            </a>
            <a href='admin.php?page=utama&panggil=mahasiswa.php&hapus={$user->ID}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus user ini?')\">
                <i class='fas fa-trash'></i> Hapus
            </a>
           
        </td>
    </tr>";
            $no++;
        endforeach;

        ?>
    </tbody>
</table>