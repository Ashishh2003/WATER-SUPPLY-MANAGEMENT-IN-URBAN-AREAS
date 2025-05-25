<?php
include 'db.php';
session_start();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Step 1: Application Form
    if (isset($_POST['step1'])) {
        $_SESSION['application'] = [
            'name' => $conn->real_escape_string($_POST['name']),
            'mobile' => $conn->real_escape_string($_POST['mobile']),
            'email' => $conn->real_escape_string($_POST['email']),
            'address' => $conn->real_escape_string($_POST['address']),
            'connection_type' => $conn->real_escape_string($_POST['connection_type'])
        ];
        header("Location: ?step=2");
        exit();
    }
    
    // Step 2: Verification
    if (isset($_POST['step2'])) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $idProof = uploadFile('id_proof', $uploadDir);
        $addressProof = uploadFile('address_proof', $uploadDir);
        $propertyProof = uploadFile('property_proof', $uploadDir);

        if ($idProof && $addressProof && $propertyProof) {
            $_SESSION['verification'] = [
                'id_proof' => $idProof,
                'address_proof' => $addressProof,
                'property_proof' => $propertyProof
            ];
            header("Location: ?step=3");
            exit();
        } else {
            $error = "Please upload all required documents (JPG, PNG, or PDF, max 2MB each)";
        }
    }
    
    // Step 3: Payment
    if (isset($_POST['step3'])) {
        $paymentMethod = $conn->real_escape_string($_POST['payment_method']);
        $transactionId = isset($_POST['transaction_id']) ? $conn->real_escape_string($_POST['transaction_id']) : '';
        
        // Generate final CAN Number
        $can = 'CAN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $amount = ($_SESSION['application']['connection_type'] == 'commercial') ? 2500.00 : 1500.00;
        
        // Insert data into connections table
        $sql = "INSERT INTO connections (
                    name, mobile, email, address, connection_type, 
                    can_number, id_proof_path, address_proof_path, 
                    property_proof_path, status, payment_method, 
                    transaction_id, amount
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssd", 
            $_SESSION['application']['name'],
            $_SESSION['application']['mobile'],
            $_SESSION['application']['email'],
            $_SESSION['application']['address'],
            $_SESSION['application']['connection_type'],
            $can,
            $_SESSION['verification']['id_proof'],
            $_SESSION['verification']['address_proof'],
            $_SESSION['verification']['property_proof'],
            $paymentMethod,
            $transactionId,
            $amount
        );
        
        if ($stmt->execute()) {
            header("Location: new_water.php?can=" . urlencode($can));
            exit();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

$current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

function uploadFile($fieldName, $uploadDir) {
    if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES[$fieldName]['type'], $allowedTypes) && $_FILES[$fieldName]['size'] <= $maxSize) {
            $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $filePath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath)) {
                return $filePath;
            }
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Water Connection | HydroCity Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00b4d8;
            --secondary: #0077b6;
            --accent: #90e0ef;
            --dark: #03045e;
            --light: #caf0f8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, rgba(0, 11, 26, 0.9) 0%, rgba(0, 42, 83, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80') no-repeat center center/cover;
            color: #fff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Water Wave Animation */
        .water-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/85486/wave.svg') repeat-x;
            background-size: 1600px 100px;
            animation: wave 15s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            z-index: -1;
            opacity: 0.8;
        }

        .water-wave:nth-of-type(2) {
            bottom: -10px;
            animation: wave 18s cubic-bezier(0.36, 0.45, 0.63, 0.53) -.125s infinite, swell 7s ease -1.25s infinite;
            opacity: 0.5;
        }

        @keyframes wave {
            0% { background-position-x: 0; }
            100% { background-position-x: 1600px; }
        }

        @keyframes swell {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        /* Floating Bubbles */
        .bubbles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            background: rgba(144, 224, 239, 0.1);
            border-radius: 50%;
            animation: rise 15s infinite ease-in;
        }

        @keyframes rise {
            0% {
                bottom: -100px;
                transform: translateX(0);
            }
            50% {
                transform: translateX(100px);
            }
            100% {
                bottom: 1080px;
                transform: translateX(-200px);
            }
        }

        /* Main Container */
        .container {
            padding-top: 40px;
            padding-bottom: 100px;
            position: relative;
            z-index: 1;
        }

        /* Connection Card */
        .connection-card {
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 42, 83, 0.3);
            overflow: hidden;
            margin: 2rem auto;
            transition: all 0.5s ease;
        }

        .connection-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 84, 166, 0.4);
            border-color: rgba(144, 224, 239, 0.4);
        }

        .connection-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(144, 224, 239, 0.3);
        }

        .connection-header h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin: 2rem auto;
            position: relative;
            max-width: 800px;
            padding: 0 20px;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(144, 224, 239, 0.3);
            z-index: 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 1;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(144, 224, 239, 0.2);
            color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            border: 2px solid rgba(144, 224, 239, 0.3);
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: var(--primary);
            color: white;
            border-color: var(--accent);
            transform: scale(1.1);
            box-shadow: 0 0 0 5px rgba(0, 180, 216, 0.3);
        }

        .step.completed .step-number {
            background: var(--secondary);
            color: white;
            border-color: var(--accent);
        }

        .step.completed .step-number::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--light);
            text-align: center;
            max-width: 100px;
        }

        /* Form Elements */
        .form-control, .form-select, .form-check-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(144, 224, 239, 0.3);
            color: white;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(144, 224, 239, 0.25);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-label {
            color: var(--light);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Document Upload */
        .document-upload {
            border: 2px dashed rgba(144, 224, 239, 0.5);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1rem;
            background: rgba(0, 119, 182, 0.1);
        }

        .document-upload:hover {
            border-color: var(--accent);
            background: rgba(0, 119, 182, 0.2);
            transform: translateY(-3px);
        }

        .document-upload i {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .document-upload h6 {
            color: white;
            font-weight: 600;
        }

        .document-upload p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .document-upload small {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
        }

        .file-preview {
            max-width: 100%;
            max-height: 150px;
            margin-top: 1rem;
            display: none;
            border-radius: 8px;
            border: 1px solid rgba(144, 224, 239, 0.3);
        }

        /* Payment Methods */
        .payment-method {
            border: 1px solid rgba(144, 224, 239, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(0, 119, 182, 0.1);
        }

        .payment-method:hover {
            border-color: var(--accent);
            background: rgba(0, 119, 182, 0.2);
            transform: translateY(-3px);
        }

        .payment-method.active {
            border-color: var(--accent);
            background: rgba(0, 119, 182, 0.3);
            box-shadow: 0 0 0 3px rgba(144, 224, 239, 0.3);
        }

        .payment-method i {
            font-size: 1.8rem;
            margin-right: 0.8rem;
            color: var(--accent);
        }

        .payment-method h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.6);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-outline-secondary {
            background: transparent;
            border: 1px solid rgba(144, 224, 239, 0.5);
            color: var(--light);
            border-radius: 30px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: rgba(144, 224, 239, 0.1);
            border-color: var(--accent);
            color: white;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-info {
            background: rgba(0, 180, 216, 0.2);
            border: 1px solid rgba(0, 180, 216, 0.3);
            color: var(--light);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ffb8b8;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .step-indicator {
                margin: 1.5rem auto;
            }
            
            .step-number {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
            
            .step-label {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .connection-card {
                margin: 1rem auto;
            }
            
            .step-indicator {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
            
            .step-indicator::before {
                display: none;
            }
            
            .step {
                flex: 0 0 calc(50% - 15px);
                margin-bottom: 15px;
            }
            
            .document-upload {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .connection-header h2 {
                font-size: 1.5rem;
            }
            
            .connection-header p {
                font-size: 0.9rem;
            }
            
            .step {
                flex: 0 0 100%;
            }
            
            .btn-primary, .btn-outline-secondary {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
            
            .payment-method {
                padding: 0.8rem;
            }
            
            .payment-method i {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Water Waves -->
    <div class="water-wave"></div>
    <div class="water-wave"></div>
    
    <!-- Floating Bubbles -->
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="connection-card">
                    <div class="connection-header">
                        <h2><i class="fas fa-tint me-2"></i> New Water Connection</h2>
                        <p class="mb-0">HydroCity Water Supply System</p>
                    </div>
                    
                    <div class="connection-body p-4">
                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step <?= $current_step >= 1 ? 'active' : '' ?> <?= $current_step > 1 ? 'completed' : '' ?>">
                                <div class="step-number">1</div>
                                <div class="step-label">Application</div>
                            </div>
                            <div class="step <?= $current_step >= 2 ? 'active' : '' ?> <?= $current_step > 2 ? 'completed' : '' ?>">
                                <div class="step-number">2</div>
                                <div class="step-label">Documents</div>
                            </div>
                            <div class="step <?= $current_step >= 3 ? 'active' : '' ?> <?= $current_step > 3 ? 'completed' : '' ?>">
                                <div class="step-number">3</div>
                                <div class="step-label">Payment</div>
                            </div>
                        </div>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $error ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Step 1: Application Form -->
                        <div id="step1" class="<?= $current_step == 1 ? '' : 'd-none' ?>">
                            <form method="POST" action="">
                                <input type="hidden" name="step1" value="1">
                                
                                <div class="mb-4">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" required
                                           value="<?= $_SESSION['application']['name'] ?? '' ?>">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Mobile Number</label>
                                        <input type="tel" class="form-control" name="mobile" required
                                               pattern="[0-9]{10}" maxlength="10"
                                               value="<?= $_SESSION['application']['mobile'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Email (Optional)</label>
                                        <input type="email" class="form-control" name="email"
                                               value="<?= $_SESSION['application']['email'] ?? '' ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Full Address</label>
                                    <textarea class="form-control" name="address" rows="3" required><?= $_SESSION['application']['address'] ?? '' ?></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Connection Type</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="connection_type" id="residential" value="residential" 
                                                <?= (!isset($_SESSION['application']['connection_type']) || $_SESSION['application']['connection_type'] == 'residential' ? 'checked' : '') ?>>
                                            <label class="form-check-label" for="residential">
                                                Residential (₹1500)
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="connection_type" id="commercial" value="commercial"
                                                <?= (isset($_SESSION['application']['connection_type']) && $_SESSION['application']['connection_type'] == 'commercial') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="commercial">
                                                Commercial (₹2500)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        Continue to Documents <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Step 2: Document Upload -->
                        <div id="step2" class="<?= $current_step == 2 ? '' : 'd-none' ?>">
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="step2" value="1">
                                
                                <div class="mb-4">
                                    <h5 class="text-center mb-4" style="color: var(--light);">Upload Required Documents</h5>
                                    
                                    <!-- ID Proof -->
                                    <div class="document-upload" onclick="document.getElementById('id_proof').click()">
                                        <i class="fas fa-id-card"></i>
                                        <h6>ID Proof</h6>
                                        <p>Aadhaar Card, Voter ID, etc.</p>
                                        <small>(JPG, PNG, or PDF, max 2MB)</small>
                                        <input type="file" id="id_proof" name="id_proof" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                                        <img id="id_proof_preview" class="file-preview img-thumbnail">
                                    </div>
                                    
                                    <!-- Address Proof -->
                                    <div class="document-upload" onclick="document.getElementById('address_proof').click()">
                                        <i class="fas fa-home"></i>
                                        <h6>Address Proof</h6>
                                        <p>Utility bill, Rental agreement, etc.</p>
                                        <small>(JPG, PNG, or PDF, max 2MB)</small>
                                        <input type="file" id="address_proof" name="address_proof" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                                        <img id="address_proof_preview" class="file-preview img-thumbnail">
                                    </div>
                                    
                                    <!-- Property Proof -->
                                    <div class="document-upload" onclick="document.getElementById('property_proof').click()">
                                        <i class="fas fa-file-contract"></i>
                                        <h6>Property Proof</h6>
                                        <p>Ownership document, Tax receipt, etc.</p>
                                        <small>(JPG, PNG, or PDF, max 2MB)</small>
                                        <input type="file" id="property_proof" name="property_proof" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                                        <img id="property_proof_preview" class="file-preview img-thumbnail">
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='?step=1'">
                                        <i class="fas fa-arrow-left me-2"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Continue to Payment <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Step 3: Payment -->
                        <div id="step3" class="<?= $current_step == 3 ? '' : 'd-none' ?>">
                            <form method="POST" action="">
                                <input type="hidden" name="step3" value="1">
                                
                                <div class="mb-4">
                                    <h5 class="text-center mb-4" style="color: var(--light);">Payment Details</h5>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Connection Fee: <strong>₹<?= 
                                            (isset($_SESSION['application']['connection_type']) && $_SESSION['application']['connection_type'] === 'commercial' ? '2500' : '1500' );
                                        ?></strong>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label mb-3">Select Payment Method</label>
                                        
                                        <!-- Credit Card -->
                                        <div class="payment-method" onclick="selectPaymentMethod(this, 'credit_card')">
                                            <div class="d-flex align-items-center">
                                                <i class="fab fa-cc-visa"></i>
                                                <h6 class="mb-0">Credit Card</h6>
                                            </div>
                                            <input type="radio" name="payment_method" value="credit_card" class="d-none">
                                        </div>
                                        
                                        <!-- Debit Card -->
                                        <div class="payment-method" onclick="selectPaymentMethod(this, 'debit_card')">
                                            <div class="d-flex align-items-center">
                                                <i class="fab fa-cc-mastercard"></i>
                                                <h6 class="mb-0">Debit Card</h6>
                                            </div>
                                            <input type="radio" name="payment_method" value="debit_card" class="d-none">
                                        </div>
                                        
                                        <!-- UPI -->
                                        <div class="payment-method" onclick="selectPaymentMethod(this, 'upi')">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-mobile-alt"></i>
                                                <h6 class="mb-0">UPI</h6>
                                            </div>
                                            <input type="radio" name="payment_method" value="upi" class="d-none">
                                        </div>
                                        
                                        <!-- Net Banking -->
                                        <div class="payment-method" onclick="selectPaymentMethod(this, 'net_banking')">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-university"></i>
                                                <h6 class="mb-0">Net Banking</h6>
                                            </div>
                                            <input type="radio" name="payment_method" value="net_banking" class="d-none">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Transaction ID (Optional)</label>
                                        <input type="text" class="form-control" name="transaction_id" placeholder="Enter if you have a reference number">
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='?step=2'">
                                        <i class="fas fa-arrow-left me-2"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submit-payment-btn" disabled>
                                        Complete Payment <i class="fas fa-check ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File upload preview functionality
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const previewId = this.id + '_preview';
                const preview = document.getElementById(previewId);
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
        
        // Payment method selection functionality
        function selectPaymentMethod(element, method) {
            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
            });
            
            // Add active class to selected method
            element.classList.add('active');
            
            // Check the corresponding radio button
            element.querySelector('input[type="radio"]').checked = true;
            
            // Enable submit button
            document.getElementById('submit-payment-btn').disabled = false;
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>