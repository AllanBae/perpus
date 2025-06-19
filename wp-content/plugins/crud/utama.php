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
		// Cek koneksi
		if ($conn->connect_error) {
		    die("Koneksi gagal: " . $conn->connect_error);
		}
		$current_user = wp_get_current_user();
		$user_nama = $current_user->display_name;
		$username = $current_user->user_login;
		$peran = $current_user->peran; #mhs

		$alamat ="";
		if($peran=="mhs"):
			$alamat = "https://mahasiswa.atmaluhur.ac.id/foto/2211500066.jpg";
		else:
			$alamat ="$jun/siti.jpg";
		endif;
		
		// Query ke tabel wp_users manual
		// 	$sql = "SELECT * FROM wp_users WHERE user_login = ?";
		// 	$stmt = $conn->prepare($sql);
		// 	$stmt->bind_param("s", $username);
		// 	$stmt->execute();
		// 	$result = $stmt->get_result();

		// // Tampilkan hasil
		// if ($result->num_rows > 0) {
    	// $row = $result->fetch_assoc();
    	// echo "<h3>Data Pengguna:</h3>";
   		// echo "ID: " . $row['ID'] . "<br>";
    	// echo "Username: " . $row['user_login'] . "<br>";
    	// echo "Email: " . $row['user_email'] . "<br>";
		// echo "Display Name: " . $row['display_name'] . "<br>";
		// echo "Peran: " . $row['peran'] . "<br>";
		// } else {
		// echo "User tidak ditemukan di tabel wp_users.";
		// }

		// 	$stmt->close();
		// 	$conn->close();
		?>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="#"><img width="20%" src="<?= $alamat ?>"></a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="collapsibleNavbar">
		      <ul class="navbar-nav">
				<?php
				if ($peran==="mhs"):
				?>
				<li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=isikel.php">Isi Keluhan</a>
		        </li>  
				<li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=lihtang.php">Lihat Tanggapan</a>
		        </li>  
				<?php else: ?>
					<li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=alert.php">Alert</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="admin.php?page=utama&panggil=modal.php">Modal</a>
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
		            <li><a class="dropdown-item" href="#">Lin</a></li>
		            <li><a class="dropdown-item" href="#">Another link</a></li>
		            <li><a class="dropdown-item" href="#">A third link</a></li>
		          </ul>
		        </li>
				<?php endif;?>
		        </ul>
		    </div>
		  </div>
		</nav>

		<div class="container-fluid mt-3">
		  <?php
		  	if (isset($_GET["panggil"])):
		  		include($_GET["panggil"]);
		  	endif;
		  	#$plugin_path = plugin_dir_path(_FILE_);
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