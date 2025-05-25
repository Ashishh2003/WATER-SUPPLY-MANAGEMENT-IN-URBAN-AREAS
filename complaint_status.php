<?php
include 'db.php';

// Function to safely fetch complaints from any table
function fetchComplaints($conn, $tableName, $typeName) {
    $complaints = [];
    
    // Check if table exists
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    if (!$result || $result->num_rows === 0) {
        return $complaints;
    }
    
    // Get available columns
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM $tableName");
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
    }
    
    // Build query with available columns
    $select = ['id'];
    $select[] = in_array('landmark', $columns) ? 'landmark' : 'NULL as landmark';
    $select[] = in_array('image_path', $columns) ? 'image_path' : 'NULL as image_path';
    $select[] = in_array('created_at', $columns) ? 'created_at' : 'NOW() as created_at';
    $select[] = "'$typeName' as type";
    $select[] = in_array('status', $columns) ? 'status' : "'pending' as status";
    
    $sql = "SELECT " . implode(', ', $select) . " FROM $tableName";
    $result = $conn->query($sql);
    
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $complaints[] = $row;
        }
    }
    
    return $complaints;
}

// Get all complaints from all tables
$allComplaints = array_merge(
    fetchComplaints($conn, 'drainage', 'drainage'),
    fetchComplaints($conn, 'sewerage', 'sewerage'),
    fetchComplaints($conn, 'illegal_connections', 'illegal_connection'),
    fetchComplaints($conn, 'water_connections', 'water_connection')
);

// Prepare chart data
$chartData = [];
for ($i = 1; $i <= 12; $i++) {
    $monthName = date('F', mktime(0, 0, 0, $i, 1));
    $chartData[$monthName] = ['completed' => 0, 'pending' => 0, 'in_progress' => 0];
}

foreach ($allComplaints as $complaint) {
    $month = date('F', strtotime($complaint['created_at']));
    $status = strtolower($complaint['status']);
    
    if (isset($chartData[$month])) {
        if ($status === 'completed') {
            $chartData[$month]['completed']++;
        } elseif ($status === 'in_progress') {
            $chartData[$month]['in_progress']++;
        } else {
            $chartData[$month]['pending']++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Status | HydroCity Solutions</title>
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

        .bubble:nth-child(1) {
            width: 40px;
            height: 40px;
            left: 10%;
            animation-duration: 8s;
        }

        .bubble:nth-child(2) {
            width: 20px;
            height: 20px;
            left: 20%;
            animation-duration: 5s;
            animation-delay: 1s;
        }

        .bubble:nth-child(3) {
            width: 50px;
            height: 50px;
            left: 35%;
            animation-duration: 7s;
            animation-delay: 2s;
        }

        .bubble:nth-child(4) {
            width: 80px;
            height: 80px;
            left: 50%;
            animation-duration: 11s;
            animation-delay: 0s;
        }

        .bubble:nth-child(5) {
            width: 35px;
            height: 35px;
            left: 55%;
            animation-duration: 6s;
            animation-delay: 1s;
        }

        .bubble:nth-child(6) {
            width: 45px;
            height: 45px;
            left: 65%;
            animation-duration: 8s;
            animation-delay: 3s;
        }

        .bubble:nth-child(7) {
            width: 25px;
            height: 25px;
            left: 75%;
            animation-duration: 7s;
            animation-delay: 2s;
        }

        .bubble:nth-child(8) {
            width: 60px;
            height: 60px;
            left: 80%;
            animation-duration: 6s;
            animation-delay: 1s;
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

        /* Glass Morphism Navbar */
        .navbar {
            position: sticky;
            top: 0;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(0, 11, 26, 0.6);
            box-shadow: 0 8px 32px rgba(0, 84, 166, 0.3);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 0 0 20px 20px;
            padding: 0 5%;
            z-index: 999;
            transition: all 0.5s ease;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.8rem;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: var(--light);
            font-weight: 500;
            padding: 10px 15px;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), var(--primary));
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .nav-link:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .nav-link:hover {
            color: white;
            text-shadow: 0 0 10px rgba(144, 224, 239, 0.7);
        }

        .nav-link i {
            margin-right: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.2);
            color: var(--accent);
        }

        .nav-link.active {
            color: white;
            font-weight: 600;
        }

        .nav-link.active::before {
            transform: scaleX(1);
        }

        /* Main Content */
        .container {
            padding-top: 40px;
            padding-bottom: 100px;
            position: relative;
            z-index: 1;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: center;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 4px;
            background: linear-gradient(to right, var(--accent), var(--primary));
            border-radius: 2px;
        }

        /* Stats Cards */
        .stats-card {
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            padding: 30px;
            color: white;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            box-shadow: 0 8px 32px rgba(0, 42, 83, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 180, 216, 0.2) 0%, rgba(0, 119, 182, 0.3) 100%);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .stats-card:hover::before {
            opacity: 1;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 40px rgba(0, 84, 166, 0.4);
            border-color: rgba(144, 224, 239, 0.4);
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--accent);
            transition: all 0.4s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.2) rotate(10deg);
            text-shadow: 0 0 20px rgba(144, 224, 239, 0.7);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-number {
            letter-spacing: 1px;
        }

        .stats-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Chart Section */
        .chart-container {
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 42, 83, 0.3);
            transition: all 0.5s ease;
        }

        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 84, 166, 0.4);
            border-color: rgba(144, 224, 239, 0.4);
        }

        .chart-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--light);
            text-align: center;
        }

        /* Search and Filter */
        .search-box {
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(144, 224, 239, 0.3);
            color: white;
            border-radius: 30px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(144, 224, 239, 0.25);
            color: white;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 30px;
            padding: 12px 25px;
            margin-left: -50px;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 180, 216, 0.4);
        }

        .filter-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(144, 224, 239, 0.3);
            color: white;
            border-radius: 30px;
            padding: 8px 20px;
            margin: 0 5px 10px;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 180, 216, 0.4);
        }

        /* Complaints Table */
        .complaint-table {
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 42, 83, 0.3);
            transition: all 0.5s ease;
        }

        .complaint-table:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 84, 166, 0.4);
            border-color: rgba(144, 224, 239, 0.4);
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 20px;
            color: white;
        }

        .table-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin: 0;
        }

        table {
            margin-bottom: 0;
            color: white;
        }

        thead {
            background: rgba(0, 119, 182, 0.5);
        }

        th {
            font-weight: 600;
            padding: 15px !important;
            border-bottom: 2px solid rgba(144, 224, 239, 0.3) !important;
        }

        td {
            padding: 12px 15px !important;
            border-top: 1px solid rgba(144, 224, 239, 0.1) !important;
            vertical-align: middle !important;
        }

        tr:hover td {
            background: rgba(144, 224, 239, 0.1) !important;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-in-progress {
            background-color: rgba(23, 162, 184, 0.2);
            color: var(--accent);
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .status-completed {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .type-badge {
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .drainage-badge {
            background-color: rgba(13, 110, 253, 0.2);
            color: var(--light);
            border: 1px solid rgba(13, 110, 253, 0.3);
        }

        .sewerage-badge {
            background-color: rgba(111, 66, 193, 0.2);
            color: #e2d4f0;
            border: 1px solid rgba(111, 66, 193, 0.3);
        }

        .illegal-badge {
            background-color: rgba(220, 53, 69, 0.2);
            color: #f5c2c7;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .water-badge {
            background-color: rgba(32, 201, 151, 0.2);
            color: #a6f0dc;
            border: 1px solid rgba(32, 201, 151, 0.3);
        }

        .complaint-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(144, 224, 239, 0.3);
        }

        .complaint-img:hover {
            transform: scale(1.1);
            box-shadow: 0 0 15px rgba(144, 224, 239, 0.5);
        }

        .no-image {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }

        .action-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 30px;
            padding: 8px 15px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 100px;
        }

        .action-btn i {
            margin-right: 5px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 180, 216, 0.4);
        }

        /* Image Modal */
        .modal-content {
            background: rgba(0, 42, 83, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.3);
            color: white;
        }

        .modal-header {
            border-bottom: 1px solid rgba(144, 224, 239, 0.2);
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
        }

        .btn-close {
            filter: invert(1);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--secondary), var(--dark));
            padding: 30px 0;
            text-align: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 -5px 20px rgba(0, 42, 83, 0.3);
            margin-top: 50px;
        }

        footer p {
            font-size: 1rem;
            opacity: 0.8;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .page-title {
                font-size: 2.5rem;
            }
            
            .stats-card {
                padding: 25px;
            }
        }

        @media (max-width: 992px) {
            .page-title {
                font-size: 2.2rem;
            }
            
            .stats-icon {
                font-size: 2rem;
            }
            
            .stats-number {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
                margin-bottom: 30px;
            }
            
            .stats-card {
                margin-bottom: 20px;
            }
            
            .chart-container {
                padding: 20px;
            }
            
            .chart-title {
                font-size: 1.5rem;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .search-box {
                margin-bottom: 20px;
            }
            
            .filter-btn {
                padding: 6px 15px;
                font-size: 0.8rem;
                margin: 0 3px 8px;
            }
            
            th, td {
                padding: 10px !important;
                font-size: 0.85rem;
            }
            
            .complaint-img, .no-image {
                width: 50px;
                height: 50px;
            }
            
            .action-btn {
                min-width: auto;
                padding: 6px 12px;
                font-size: 0.8rem;
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tint me-2"></i>HydroCity
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-clipboard-list me-1"></i> Complaints</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.php"><i class="fas fa-info-circle me-1"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php"><i class="fas fa-lock me-1"></i> Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="page-title">Complaint Management Dashboard</h1>
        
        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-md-4 mb-4">
                <div class="stats-card text-center">
                    <i class="fas fa-clipboard-list stats-icon"></i>
                    <h2 class="stats-number"><?= count($allComplaints) ?></h2>
                    <p class="stats-label">Total Complaints</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stats-card text-center">
                    <i class="fas fa-check-circle stats-icon"></i>
                    <h2 class="stats-number"><?= array_sum(array_column($chartData, 'completed')) ?></h2>
                    <p class="stats-label">Completed</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stats-card text-center">
                    <i class="fas fa-clock stats-icon"></i>
                    <h2 class="stats-number"><?= array_sum(array_column($chartData, 'pending')) ?></h2>
                    <p class="stats-label">Pending</p>
                </div>
            </div>
        </div>
        
        <!-- Chart Section -->
        <div class="chart-container mb-5">
            <h3 class="chart-title"><i class="fas fa-chart-line me-2"></i>Monthly Complaint Trends</h3>
            <canvas id="complaintChart" height="250"></canvas>
        </div>
        
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="search-box input-group">
                    <input type="text" class="form-control search-input" placeholder="Search complaints..." id="searchInput">
                    <button class="btn btn-primary search-btn" type="button" id="searchBtn">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </div>
            <div class="col-12 text-center">
                <button class="filter-btn active" data-filter="all">All Complaints</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
                <button class="filter-btn" data-filter="pending">Pending</button>
                <button class="filter-btn" data-filter="in_progress">In Progress</button>
            </div>
        </div>
        
        <!-- Complaints Table -->
        <div class="complaint-table mb-5">
            <div class="table-header">
                <h3 class="table-title"><i class="fas fa-list me-2"></i>All Complaints</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="complaintsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Landmark</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Date Reported</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allComplaints as $complaint): 
                            $status = strtolower($complaint['status']);
                            $statusClass = 'status-' . str_replace(' ', '-', $status);
                            $statusText = ucwords(str_replace('_', ' ', $status));
                            
                            $typeClass = str_replace('_', '-', $complaint['type']) . '-badge';
                            $typeText = ucwords(str_replace('_', ' ', $complaint['type']));
                        ?>
                        <tr data-status="<?= $status ?>" data-type="<?= $complaint['type'] ?>">
                            <td><?= htmlspecialchars($complaint['id']) ?></td>
                            <td>
                                <span class="type-badge <?= $typeClass ?>"><?= $typeText ?></span>
                            </td>
                            <td><?= htmlspecialchars($complaint['landmark'] ?? 'N/A') ?></td>
                            <td>
                                <?php if (!empty($complaint['image_path']) && $complaint['image_path'] !== 'NULL'): ?>
                                <img src="<?= htmlspecialchars($complaint['image_path']) ?>" 
                                     class="complaint-img" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imageModal"
                                     data-img-src="<?= htmlspecialchars($complaint['image_path']) ?>">
                                <?php else: ?>
                                <div class="no-image">
                                    <span>No Image</span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                            </td>
                            <td><?= date('M d, Y', strtotime($complaint['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm action-btn">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complaint Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" class="img-fluid rounded" id="modalImage">
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 HydroCity Solutions | All rights reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize Chart
        const ctx = document.getElementById('complaintChart').getContext('2d');
        const complaintChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($chartData)) ?>,
                datasets: [
                    {
                        label: 'Completed',
                        data: <?= json_encode(array_column($chartData, 'completed')) ?>,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'In Progress',
                        data: <?= json_encode(array_column($chartData, 'in_progress')) ?>,
                        backgroundColor: 'rgba(23, 162, 184, 0.7)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pending',
                        data: <?= json_encode(array_column($chartData, 'pending')) ?>,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#fff',
                            font: {
                                family: "'Montserrat', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 42, 83, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(144, 224, 239, 0.5)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Complaints',
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(144, 224, 239, 0.1)'
                        },
                        ticks: {
                            color: '#fff'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month',
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(144, 224, 239, 0.1)'
                        },
                        ticks: {
                            color: '#fff'
                        }
                    }
                }
            }
        });

        // Image Modal
        document.getElementById('imageModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imgSrc = button.getAttribute('data-img-src');
            document.getElementById('modalImage').src = imgSrc;
        });

        // Search and Filter
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const tableRows = document.querySelectorAll('#complaintsTable tbody tr');
            
            // Search function
            function searchComplaints() {
                const term = searchInput.value.toLowerCase();
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            }
            
            // Filter function
            function filterComplaints(status) {
                tableRows.forEach(row => {
                    if (status === 'all' || row.getAttribute('data-status') === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Event listeners
            searchBtn.addEventListener('click', searchComplaints);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') searchComplaints();
            });
            
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filterComplaints(this.dataset.filter);
                });
            });
        });

        // Animate navbar on scroll
        const navbar = document.querySelector('.navbar');
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', () => {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>