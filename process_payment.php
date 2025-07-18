<?php
require_once 'config/database.php';
require_once 'config/midtrans_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $motor_id = $_POST['motor_id'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $amount = $_POST['amount'];
    
    // Generate unique order ID
    $order_id = 'ORDER-' . time() . '-' . $motor_id;
    
    // Prepare transaction details for Midtrans
    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => (int)$amount,
    );
    
    // Customer details
    $customer_details = array(
        'first_name' => $customer_name,
        'email' => $customer_email,
        'phone' => $customer_phone,
    );
    
    // Get motor details from database
    $motor_query = "SELECT * FROM motor WHERE id = $motor_id";
    $motor_result = mysqli_query($conn, $motor_query);
    $motor = mysqli_fetch_assoc($motor_result);
    
    // Item details
    $item_details = array(
        array(
            'id' => $motor_id,
            'price' => (int)$amount,
            'quantity' => 1,
            'name' => $motor['merk'] . ' ' . $motor['model'] . ' - ' . $motor['tahun'],
        )
    );
    
    // Transaction data for Midtrans
    $transaction = array(
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
    );
    
    try {
        // Get Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);
        
        // Save transaction to database
        $insert_query = "INSERT INTO transactions (order_id, motor_id, customer_name, customer_email, customer_phone, amount, snap_token) 
                        VALUES ('$order_id', '$motor_id', '$customer_name', '$customer_email', '$customer_phone', '$amount', '$snapToken')";
        
        if (mysqli_query($conn, $insert_query)) {
            // Redirect to payment page with snap token
            header("Location: payment.php?token=" . $snapToken . "&order_id=" . $order_id);
            exit();
        } else {
            throw new Exception('Failed to save transaction to database');
        }
        
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
?>
