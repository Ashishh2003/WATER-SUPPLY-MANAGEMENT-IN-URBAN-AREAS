<?php
session_start();
include 'db.php'; // Database connection

// Check if completed_at column exists, if not add it
$check_column = $conn->query("SHOW COLUMNS FROM illegal_connections LIKE 'completed_at'");
if ($check_column->num_rows == 0) {
    $conn->query("ALTER TABLE illegal_connections ADD COLUMN completed_at TIMESTAMP NULL DEFAULT NULL AFTER status");
}

// Handle marking as complete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_id'])) {
    $id = intval($_POST['complete_id']);
    $sql = "UPDATE illegal_connections SET status = 'completed', completed_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Complaint marked as completed successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating complaint status";
        $_SESSION['message_type'] = "danger";
    }
    $stmt->close();
    header("Location: view_illegal.php");
    exit();
}

// Fetch all illegal connections from database
$sql = "SELECT * FROM illegal_connections ORDER BY 
        CASE WHEN status = 'pending' THEN 1 ELSE 2 END, 
        created_at DESC";
$result = $conn->query($sql);
$connections = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $connections[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Meter Fixation - HydroCity Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 42, 83, 0.8), rgba(0, 11, 26, 0.9)), 
                        url('https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80') no-repeat center center/cover;
            color: white;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Water Waves Animation */
        .water-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="%2390e0ef" opacity=".25"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" fill="%2390e0ef" opacity=".5"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%2390e0ef"/></svg>') repeat-x;
            background-size: 1600px 100px;
            animation: wave 15s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            z-index: -1;
            opacity: 0.6;
        }

        @keyframes wave {
            0% { background-position-x: 0; }
            100% { background-position-x: 1600px; }
        }

        /* Navbar */
        .navbar {
            background: rgba(2, 8, 23, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 15px 0;
            border-bottom: 1px solid rgba(144, 224, 239, 0.2);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            margin: 0 10px;
            position: relative;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--accent);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 100%;
        }

        /* Main Content */
        .main-content {
            padding: 80px 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent);
            border-radius: 3px;
        }

        .connections-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 30px rgba(0, 84, 166, 0.2);
        }

        .connection-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--accent);
            transition: all 0.3s ease;
        }

        .connection-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 84, 166, 0.3);
        }

        .connection-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .connection-id {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent);
            background: rgba(144, 224, 239, 0.1);
            padding: 5px 10px;
            border-radius: 5px;
        }

        .connection-date {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .connection-landmark {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .connection-image {
            margin-top: 15px;
        }

        .connection-image img {
            max-width: 100%;
            border-radius: 8px;
            border: 2px solid rgba(144, 224, 239, 0.3);
            transition: all 0.3s ease;
        }

        .connection-image img:hover {
            transform: scale(1.02);
            border-color: var(--accent);
        }

        .no-connections {
            text-align: center;
            padding: 40px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid #ffc107;
        }
        
        .status-completed {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }
        
        .complete-btn {
            background: var(--primary);
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .complete-btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        
        .completed-info {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
        }

        /* Message Alert */
        .message-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            animation: slideIn 0.5s forwards, fadeOut 0.5s forwards 3s;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        /* Footer */
        footer {
            background: rgba(2, 8, 23, 0.9);
            padding: 40px 0 20px;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--accent), transparent);
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
            margin-bottom: 20px;
            display: inline-block;
        }

        .footer-links h5 {
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--accent);
            padding-left: 5px;
        }

        .social-links a {
            color: white;
            background: rgba(144, 224, 239, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .main-content {
                padding: 60px 20px;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .connection-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .connections-card {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .connection-item {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Water Wave Animation -->
    <div class="water-wave"></div>

    <!-- Message Alert -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message-alert">
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="HydroCity Solutions">
                HydroCity Solutions
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="illegal_connection.php">Report Illegal Connection</a></li>
                    <li class="nav-item"><a class="nav-link active" href="view_illegal.php">View Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="complaint_status.php">Complaint Status</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="main-content">
        <div class="container">
            <h1 class="page-title">Meter fixiation Reports</h1>
            
            <div class="connections-card">
                <?php if (empty($connections)): ?>
                    <div class="no-connections">
                        <i class="fas fa-info-circle fa-3x mb-3" style="color: var(--accent);"></i>
                        <h3>No illegal connection reports found</h3>
                        <p class="mt-3">Be the first to report an illegal water connection in your area</p>
                        <a href="illegal_connection.php" class="btn btn-primary mt-3">
                            <i class="fas fa-plus-circle me-2"></i> Report Now
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($connections as $connection): ?>
                        <div class="connection-item">
                            <div class="connection-header">
                                <div>
                                    <span class="connection-id">Report #<?= htmlspecialchars($connection['id']) ?></span>
                                    <span class="status-badge <?= $connection['status'] == 'completed' ? 'status-completed' : 'status-pending' ?>">
                                        <?= htmlspecialchars(ucfirst($connection['status'])) ?>
                                    </span>
                                </div>
                                <span class="connection-date">Reported on <?= date('M d, Y h:i A', strtotime($connection['created_at'])) ?></span>
                            </div>
                            
                            <div class="connection-landmark">
                                <i class="fas fa-map-marker-alt me-2" style="color: var(--accent);"></i>
                                <?= htmlspecialchars($connection['landmark']) ?>
                            </div>
                            
                            <div class="connection-image">
                                <a href="<?= htmlspecialchars($connection['image_path']) ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($connection['image_path']) ?>" alt="Illegal connection evidence" class="img-fluid">
                                </a>
                            </div>
                            
                            <?php if ($connection['status'] == 'completed' && !empty($connection['completed_at'])): ?>
                                <div class="completed-info">
                                    <i class="fas fa-check-circle me-1" style="color: #28a745;"></i>
                                    Completed on <?= date('M d, Y h:i A', strtotime($connection['completed_at'])) ?>
                                </div>
                            <?php elseif ($connection['status'] == 'pending'): ?>
                                <form method="POST" action="view_illegal.php" class="d-inline">
                                    <input type="hidden" name="complete_id" value="<?= $connection['id'] ?>">
                                    <button type="submit" class="complete-btn">
                                        <i class="fas fa-check me-1"></i> Mark as Complete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <span class="footer-logo">HydroCity Solutions</span>
                    <p>Innovative water management solutions for sustainable urban living.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="home.php">Home</a></li>
                            <li><a href="admin.php">Admin Login</a></li>
                            <li><a href="aboutus.php">About Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Services</h5>
                        <ul>
                            <li><a href="#">Water Infrastructure</a></li>
                            <li><a href="#">Sewerage Systems</a></li>
                            <li><a href="#">Water Treatment</a></li>
                            <li><a href="#">Smart Monitoring</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Contact</h5>
                        <ul>
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Waterfront, Hydropolis</li>
                            <li><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                            <li><i class="fas fa-envelope me-2"></i> info@hydrocity.com</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 HydroCity Solutions. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>