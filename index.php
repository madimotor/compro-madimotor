<?php 
$page_title = 'Beranda - Temukan Motor Impian Anda';
include 'templates/header.php'; 

// Ambil data motor dari database
$query = "SELECT motor.*, users.nama as nama_penjual FROM motor JOIN users ON motor.user_id = users.id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="p-5 mb-4 bg-light rounded-3 text-center">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Selamat Datang di Garasi Motor</h1>
        <p class="fs-4">Platform jual beli motor bekas terpercaya di Indonesia.</p>
    </div>
</div>

<h2 class="mb-4">Daftar Motor Terbaru</h2>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($motor = mysqli_fetch_assoc($result)): ?>
            <div class="col">
                <div class="card h-100">
                    <img src="assets/uploads/<?php echo htmlspecialchars($motor['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($motor['merk'] . ' ' . $motor['model']); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($motor['merk'] . ' ' . $motor['model']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Tahun <?php echo htmlspecialchars($motor['tahun']); ?></h6>
                        <p class="card-text fw-bold text-success fs-5">Rp <?php echo number_format($motor['harga'], 0, ',', '.'); ?></p>
                        <p class="card-text"><small class="text-muted">Penjual: <?php echo htmlspecialchars($motor['nama_penjual']); ?></small></p>
                    </div>
                    <div class="card-footer">
                        <a href="detail_motor.php?id=<?php echo $motor['id']; ?>" class="btn btn-primary w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <p class="text-center">Belum ada motor yang dijual.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>