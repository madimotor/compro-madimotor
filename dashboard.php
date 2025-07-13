<?php 
$page_title = 'Dashboard';
include 'templates/header.php'; 

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

// Ambil data motor yang diupload oleh pengguna ini
$query = "SELECT * FROM motor WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

?>

<h1 class="mb-4">Selamat Datang, <?php echo htmlspecialchars($user_nama); ?>!</h1>
<p>Ini adalah halaman dashboard Anda. Di sini Anda bisa mengelola motor yang Anda jual.</p>
<a href="tambah_motor.php" class="btn btn-primary mb-4">
    <i class="bi bi-plus-circle"></i> Jual Motor Baru
</a>

<h3 class="mt-4">Motor yang Anda Jual:</h3>
<hr>

<div class="row">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($motor = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="assets/uploads/<?php echo htmlspecialchars($motor['gambar']); ?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($motor['merk'] . ' ' . $motor['model']); ?></h5>
                    <p class="card-text">Rp <?php echo number_format($motor['harga']); ?></p>
                    <a href="detail_motor.php?id=<?php echo $motor['id']; ?>" class="btn btn-info btn-sm">Detail</a>
                    </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Anda belum menjual motor apapun.</p>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>