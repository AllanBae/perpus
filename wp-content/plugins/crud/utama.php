<?php
	/*
		Plugin Name: CRUD Kelompok Ku
		Description: Ini adalah plugin CRUD kelompok UAS kami yang beranggotakan 6 mahasiswa/i super power, yakni Pur, Per, Por, Par, Pir, dan Bor.
		Author: Pur, Per, Por, Par, Pir, dan Bor
		Author URI: https://www.facebook.com/kelasberat88
		Plugin URI: https://id.wordpress.org/plugins/crud-ku
		Version: 1.0.0
	*/
?>

<?php
	function modulku() {
		$jun = plugin_dir_url(__FILE__);
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$current_user = wp_get_current_user();
		$user_login = $current_user->user_login;
		// Menggunakan prepared statement untuk menghindari SQL injection
		$stmt = $conn->prepare("SELECT * FROM wp_users WHERE user_login = ?");
		$stmt->bind_param("s", $user_login);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($row = $result->fetch_assoc()) {
		    // Tampilkan data pengguna, sesuai kolom yang terdapat di tabel wp_users
		    echo "ID: " . htmlspecialchars($row['ID']) . "<br>";
		    echo "Username: " . htmlspecialchars($row['user_login']) . "<br>";
		    echo "Display Name: " . htmlspecialchars($row['display_name']) . "<br>";
		    echo "Email: " . htmlspecialchars($row['user_email']) . "<br>";
		    echo "Peran: " . htmlspecialchars($row['peran']) . "<br>";
		} else {
		    echo "Pengguna tidak ditemukan.";
		}
		// Cek koneksi
		if ($conn->connect_error) {
		    die("Koneksi gagal: " . $conn->connect_error);
		}
		?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="#"><img width="25%" src="<?= $jun ?>/self-pic.jpg"></a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="collapsibleNavbar">
		      <ul class="navbar-nav">
		        <li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=alert.php">Alert</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=modal.php">Modal</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="#">Link</a>
		        </li>
				<li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=isikel.php">Isi Keluhan</a>
		        </li>
				<li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=isikel.php">Lihat</a>
		        </li>  
		        <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Master</a>
		          <ul class="dropdown-menu">
		            <li><a class="dropdown-item" href="admin.php?page=utama&panggil=jenis.php">Jenis Keluhan</a></li>
		            <li><a class="dropdown-item" href="admin.php?page=utama&panggil=bagian.php">Bagian</a></li>
		            <li><a class="dropdown-item" href="admin.php?page=utama&panggil=mahasiswa.php">Mahasiswa</a></li>
		          </ul>
		        </li>
		         <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dropdown</a>
		          <ul class="dropdown-menu">
		            <li><a class="dropdown-item" href="#">Link</a></li>
		            <li><a class="dropdown-item" href="#">Another link</a></li>
		            <li><a class="dropdown-item" href="#">A third link</a></li>
		          </ul>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>

		<div class="container-fluid mt-3">
		  <?php
		  	if (isset($_GET["panggil"])):
		  		include($_GET["panggil"]);
		  	endif;
		  	#$plugin_path = plugin_dir_path(__FILE__);
				#echo $plugin_path;
				
		  ?>
		</div>
		<?php
	}
?>

<?php
	function tambah_menu_modulku() {
    add_menu_page(
        'SI CRUD Pertemanan 6 Ksatria',         // Judul halaman / title
        'CRUD SI',         // Nama label menu
        'read',  // Hak akses / capabalities
        'utama',         // Slug menu / page value
        'modulku',    // Callback fungsi / modul yang dikerjakan
        'dashicons-share-alt' // Ikon menu / dashicon
    );
	}

	add_action('admin_menu', 'tambah_menu_modulku');
?>