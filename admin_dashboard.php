<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HydroCity Solutions</title>
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

        /* Dashboard Section */
        .dashboard-section {
            padding: 80px 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 30px rgba(0, 84, 166, 0.2);
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 119, 182, 0.3);
            border-color: var(--accent);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--accent);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: white;
        }

        .card-description {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        /* Footer */
        footer {
            background: rgba(2, 8, 23, 0.9);
            padding: 40px 0 20px;
            position: relative;
            margin-top: 80px;
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
        @media (max-width: 768px) {
            .dashboard-section {
                padding: 50px 20px;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 1.8rem;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Water Wave Animation -->
    <div class="water-wave"></div>

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
                    <li class="nav-item"><a class="nav-link active" href="#">Admin Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="container">
            <h2 class="dashboard-title">Admin Dashboard</h2>
            
            <div class="dashboard-grid">
                <div class="dashboard-card" onclick="window.location.href='views_complaint.php'">
                    <div class="card-icon">
                        <i class="fas fa-water"></i>
                    </div>
                    <h3 class="card-title">Drainage Complaints</h3>
                    <p class="card-description">View and manage all drainage system complaints from citizens</p>
                </div>
                
                <div class="dashboard-card" onclick="window.location.href='view_sewerage.php'">
                    <div class="card-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h3 class="card-title">Sewerage Complaints</h3>
                    <p class="card-description">Manage sewerage system issues reported by residents</p>
                </div>
                <div class="dashboard-card" onclick="window.location.href='new_water.php'">
                    <div class="card-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h3 class="card-title">Water Management</h3>
                    <p class="card-description">Monitor and control water supply systems</p>
                </div>
                
                <div class="dashboard-card" onclick="window.location.href='view_illegal.php'">
                    <div class="card-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <h3 class="card-title">Illegal Connections</h3>
                    <p class="card-description">Track and address unauthorized water connections</p>
                </div>
                
                <div class="dashboard-card" onclick="window.location.href='new_meterfixation.php'">
                    <div class="card-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 class="card-title">Meter Fixation</h3>
                    <p class="card-description">Manage water meter installation and maintenance</p>
                </div>
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
                            <li><a href="#">Admin Dashboard</a></li>
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