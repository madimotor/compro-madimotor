<?php
require_once 'config/database.php';
require_once 'templates/header.php';

$order_id = $_GET['order_id'] ?? '';

if (empty($order_id)) {
    header("Location: index.php");
    exit();
}

// Get transaction details
$query = "SELECT t.*, m.merk, m.model, m.tahun FROM transactions t 
          JOIN motor m ON t.motor_id = m.id 
          WHERE t.order_id = '$order_id'";
$result = mysqli_query($conn, $query);
$transaction = mysqli_fetch_assoc($result);

if (!$transaction) {
    header("Location: index.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4><i class="fas fa-clock"></i> Pembayaran Pending</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-clock text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-warning">Pembayaran Sedang Diproses</h3>
                    <p class="lead">Pembayaran Anda sedang dalam proses verifikasi.</p>
                    
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-6 text-left">
                                <h5>Detail Transaksi</h5>
                                <p><strong>Order ID:</strong> <?php echo $transaction['order_id']; ?></p>
                                <p><strong>Motor:</strong> <?php echo $transaction['merk'] . ' ' . $transaction['model'] . ' - ' . $transaction['tahun']; ?></p>
                                <p><strong>Nama:</strong> <?php echo $transaction['customer_name']; ?></p>
                                <p><strong>Email:</strong> <?php echo $transaction['customer_email']; ?></p>
                                <p><strong>Total:</strong> Rp <?php echo number_format($transaction['amount'], 0, ',', '.'); ?></p>
                                <p><strong>Status:</strong> <span class="badge badge-warning">Pending</span></p>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning">
                                    <h6>Informasi Pembayaran:</h6>
                                    <ul class="text-left">
                                        <li>Silakan selesaikan pembayaran sesuai instruksi</li>
                                        <li>Pembayaran akan dikonfirmasi otomatis</li>
                                        <li>Jika ada masalah, hubungi customer service</li>
                                        <li>Pembayaran akan expired dalam 24 jam</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                        <button onclick="location.reload()" class="btn btn-secondary">Refresh Status</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto refresh every 30 seconds to check payment status
setTimeout(function() {
    location.reload();
}, 30000);
</script>

<?php require_once 'templates/footer.php'; ?>
