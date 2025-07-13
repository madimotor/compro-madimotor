<?php
// Kita tidak mengatur judul halaman di sini dulu, karena judulnya akan dinamis
// tergantung data motor yang ditemukan.
include 'templates/header.php'; 

// 1. Cek apakah ada ID di URL dan apakah ID itu valid (angka)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Jika tidak ada ID atau ID bukan angka, tampilkan error dan hentikan script
    echo "<div class='alert alert-danger'>ID Motor tidak valid.</div>";
    include 'templates/footer.php';
    exit();
}

$motor_id = (int)$_GET['id']; // Ambil ID dan pastikan tipenya integer untuk keamanan

// 2. Buat query untuk mengambil data motor SPESIFIK beserta nama penjualnya
// Kita menggunakan JOIN untuk menggabungkan tabel motor dan users
$query = "SELECT motor.*, users.nama AS nama_penjual, users.email AS email_penjual 
          FROM motor 
          JOIN users ON motor.user_id = users.id 
          WHERE motor.id = $motor_id";

$result = mysqli_query($conn, $query);

// 3. Cek apakah motor dengan ID tersebut ditemukan (harus ada 1 baris data)
if (mysqli_num_rows($result) == 1) {
    // Jika ditemukan, ambil datanya
    $motor = mysqli_fetch_assoc($result);

    // Sekarang kita bisa set judul halaman secara dinamis
    $page_title = "Detail " . htmlspecialchars($motor['merk'] . ' ' . $motor['model']);
    // Trik kecil untuk menampilkan judul yang benar di tab browser
    echo "<script>document.title = '" . addslashes($page_title) . "';</script>";

?>

<div class="row">
    <div class="col-md-7">
        <img src="assets/uploads/<?php echo htmlspecialchars($motor['gambar']); ?>" class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($motor['merk']); ?>">
    </div>

    <div class="col-md-5">
        <h1><?php echo htmlspecialchars($motor['merk'] . ' ' . $motor['model']); ?></h1>
        <h2 class="text-success fw-bold mb-4">Rp <?php echo number_format($motor['harga'], 0, ',', '.'); ?></h2>
        
        <div class="card">
            <div class="card-header">
                <strong>Spesifikasi Utama</strong>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Merk
                    <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($motor['merk']); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Model
                    <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($motor['model']); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Tahun Produksi
                    <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($motor['tahun']); ?></span>
                </li>
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <strong>Informasi Penjual</strong>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nama:</strong> <?php echo htmlspecialchars($motor['nama_penjual']); ?></li>
                <li class="list-group-item"><strong>Kontak (Email):</strong> <?php echo htmlspecialchars($motor['email_penjual']); ?></li>
            </ul>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="https://wa.me/081211376296" target="_blank"><strong>Hubungi Penjual</strong></a> <?php echo htmlspecialchars($motor['nama_penjual']); ?></li>
            </ul>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Deskripsi</h3>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($motor['deskripsi'])); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
</div>


<?php
} else {
    // 5. Jika motor dengan ID tersebut tidak ditemukan
    echo "<div class='alert alert-warning text-center'>";
    echo "<h2><i class='bi bi-exclamation-triangle'></i> Oops!</h2>";
    echo "<p>Motor yang Anda cari tidak ditemukan atau mungkin sudah terjual.</p>";
    echo "<a href='index.php' class='btn btn-primary'>Kembali ke Beranda</a>";
    echo "</div>";
}

include 'templates/footer.php'; 
?>