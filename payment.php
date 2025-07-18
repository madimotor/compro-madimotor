<?php
require_once 'config/database.php';
require_once 'templates/header.php';

$snap_token = $_GET['token'] ?? '';
$order_id = $_GET['order_id'] ?? '';

if (empty($snap_token) || empty($order_id)) {
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
                <div class="card-header">
                    <h4>Pembayaran</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Detail Pesanan</h5>
                            <p><strong>Order ID:</strong> <?php echo $transaction['order_id']; ?></p>
                            <p><strong>Motor:</strong> <?php echo $transaction['merk'] . ' ' . $transaction['model'] . ' - ' . $transaction['tahun']; ?></p>
                            <p><strong>Nama:</strong> <?php echo $transaction['customer_name']; ?></p>
                            <p><strong>Email:</strong> <?php echo $transaction['customer_email']; ?></p>
                            <p><strong>Total:</strong> Rp <?php echo number_format($transaction['amount'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <button id="pay-button" class="btn btn-primary btn-lg">Bayar Sekarang</button>
                                <div id="snap-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo MIDTRANS_CLIENT_KEY; ?>"></script>
<script type="text/javascript">
document.getElementById('pay-button').onclick = function(){
    snap.pay('<?php echo $snap_token; ?>', {
        onSuccess: function(result){
            window.location.href = 'payment_success.php?order_id=<?php echo $order_id; ?>';
        },
        onPending: function(result){
            window.location.href = 'payment_pending.php?order_id=<?php echo $order_id; ?>';
        },
        onError: function(result){
            alert('Pembayaran gagal!');
        }
    });
};
</script>

<?php require_once 'templates/footer.php'; ?>
