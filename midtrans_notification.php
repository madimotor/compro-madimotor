<?php
require_once 'config/database.php';
require_once 'config/midtrans_config.php';

// Get notification body
$json_result = file_get_contents('php://input');
$result = json_decode($json_result);

if (empty($result)) {
    http_response_code(400);
    exit();
}

$order_id = $result->order_id;
$transaction_status = $result->transaction_status;
$fraud_status = $result->fraud_status ?? '';
$payment_type = $result->payment_type ?? '';

// Verify notification authenticity
try {
    $notification = new \Midtrans\Notification();
} catch (Exception $e) {
    http_response_code(400);
    exit();
}

$transaction = $notification->transaction_status;
$type = $notification->payment_type;
$order_id = $notification->order_id;
$fraud = $notification->fraud_status;

// Update transaction status based on Midtrans notification
if ($transaction == 'capture') {
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            $status = 'pending';
        } else {
            $status = 'success';
        }
    }
} else if ($transaction == 'settlement') {
    $status = 'success';
} else if ($transaction == 'pending') {
    $status = 'pending';
} else if ($transaction == 'deny') {
    $status = 'failed';
} else if ($transaction == 'expire') {
    $status = 'expired';
} else if ($transaction == 'cancel') {
    $status = 'failed';
}

// Update database
$update_query = "UPDATE transactions SET 
                status = '$status', 
                payment_type = '$type',
                transaction_time = NOW(),
                updated_at = NOW()
                WHERE order_id = '$order_id'";

if (mysqli_query($conn, $update_query)) {
    // Log the notification for debugging
    $log_data = [
        'order_id' => $order_id,
        'status' => $status,
        'payment_type' => $type,
        'transaction_status' => $transaction,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents(
        'logs/midtrans_notifications.log', 
        json_encode($log_data) . "\n", 
        FILE_APPEND | LOCK_EX
    );
    
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(500);
    echo "Database update failed";
}
?>
