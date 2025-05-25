<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: linear-gradient(to right, #11998e, #38ef7d);
        }
        
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        
        h2 {
            color: #333;
        }
        
        input, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        
        button {
            background: #38ef7d;
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        
        button:hover {
            background: #11998e;
        }
        
        #qrCode {
            margin-top: 20px;
        }
        
        .payment-slip {
            display: none;
            background: #e3f2fd;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pay Your Water Bill</h2>
        <form id="billPaymentForm" onsubmit="return handleSubmit(event)">
            <input type="text" id="name" placeholder="Enter your name" required>
            <input type="text" id="mobile" placeholder="Enter your mobile number" required>
            <input type="text" id="can" placeholder="Enter CAN Number" required>
            <div>Amount to Pay: ₹<span id="amount">0.00</span></div>
            <input type="hidden" id="amountInput">
            <button type="submit">Pay Now</button>
        </form>
        <div id="qrCode"></div>
        <div class="payment-slip" id="paymentSlip">
            <h3>Payment Slip</h3>
            <p><strong>Name:</strong> <span id="slipName"></span></p>
            <p><strong>Mobile:</strong> <span id="slipMobile"></span></p>
            <p><strong>CAN Number:</strong> <span id="slipCAN"></span></p>
            <p><strong>Amount Paid:</strong> ₹<span id="slipAmount"></span></p>
            <button onclick="downloadSlip()">Download Slip</button>
        </div>
    </div>

    <script>
        function calculateAmount() {
            const totalAmount = (Math.random() * 500).toFixed(2);
            document.getElementById('amount').textContent = totalAmount;
            document.getElementById('amountInput').value = totalAmount;
        }
        
        function handleSubmit(event) {
            event.preventDefault();
            document.getElementById('slipName').textContent = document.getElementById('name').value;
            document.getElementById('slipMobile').textContent = document.getElementById('mobile').value;
            document.getElementById('slipCAN').textContent = document.getElementById('can').value;
            document.getElementById('slipAmount').textContent = document.getElementById('amount').textContent;
            document.getElementById('paymentSlip').style.display = 'block';
            alert('Payment Successful!');
        }

        function downloadSlip() {
            alert('Downloading Payment Slip...');
        }

        window.onload = calculateAmount;
    </script>
</body>
</html>
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection (replace with the correct path if necessary)
include 'db.php'; // Make sure this path is correct

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $can = $_POST['can'];
    
    // Get the amount from the POST data and ensure it is a valid decimal value
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.00; // Ensure it's a valid float
    $billingDate = date('Y-m-d'); // Get current date in YYYY-MM-DD format

    // Check if amount is valid (greater than 0)
    if ($amount > 0) {
        // Insert payment details into the database
        $sql = "INSERT INTO payments (name, mobile, can, amount, billing_date, payment_status) 
                VALUES ('$name', '$mobile', '$can', '$amount', '$billingDate', 'paid')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Fetch the inserted data to display on the slip
            $paymentId = mysqli_insert_id($conn);
            $paymentDetails = [
                'name' => $name,
                'mobile' => $mobile,
                'can' => $can,
                'amount' => $amount,
                'billing_date' => $billingDate
            ];

            // Generate the payment slip (You can also generate a PDF at this point)
            echo '<h3>Payment Slip</h3>';
            echo '<p><strong>Name:</strong> ' . $paymentDetails['name'] . '</p>';
            echo '<p><strong>Mobile:</strong> ' . $paymentDetails['mobile'] . '</p>';
            echo '<p><strong>CAN Number:</strong> ' . $paymentDetails['can'] . '</p>';
            echo '<p><strong>Amount Paid:</strong> ₹' . $paymentDetails['amount'] . '</p>';
            echo '<p><strong>Billing Date:</strong> ' . $paymentDetails['billing_date'] . '</p>';

            // Generate the QR code for the slip
            echo '<div id="slipQRCode"></div>';
            echo '<script>
                const qr = new QRious({
                    element: document.getElementById("slipQRCode"),
                    value: "Payment Successful!\\nName: ' . $paymentDetails['name'] . '\\nMobile: ' . $paymentDetails['mobile'] . '\\nCAN Number: ' . $paymentDetails['can'] . '\\nAmount Paid: ₹' . $paymentDetails['amount'] . '\\nBilling Date: ' . $paymentDetails['billing_date'] . '",
                    size: 150
                });
            </script>';

            // You can optionally redirect to the home page after a few seconds
            echo '<script>setTimeout(function() { window.location.href = "home.php"; }, 3000);</script>';

        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Invalid amount";
    }

    // Close database connection
    mysqli_close($conn);
}
?>
