<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HydroCity Solutions</title>
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

        /* Hero Section */
        .hero-section {
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(0, 180, 216, 0.1) 0%, rgba(0, 11, 26, 0.7) 70%);
            z-index: -1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(0, 180, 216, 0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* About Cards */
        .about-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .about-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 180, 216, 0.1) 0%, rgba(0, 119, 182, 0.2) 100%);
            z-index: -1;
        }

        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 84, 166, 0.4);
            border-color: rgba(144, 224, 239, 0.3);
        }

        .about-card i {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 20px;
            background: rgba(144, 224, 239, 0.1);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .about-card:hover i {
            transform: scale(1.1);
            background: rgba(144, 224, 239, 0.2);
        }

        .about-card h3 {
            font-weight: 600;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .about-card h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--accent);
            border-radius: 3px;
        }

        /* Carousel */
        .carousel-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            margin: 50px 0;
        }

        .carousel-item img {
            height: 500px;
            object-fit: cover;
            filter: brightness(0.7);
        }

        .carousel-caption {
            background: rgba(2, 8, 23, 0.7);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            text-align: center;
        }

        .carousel-caption h5 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--light);
            margin-bottom: 10px;
        }

        /* Team Section */
        .team-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s ease;
            text-align: center;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 84, 166, 0.4);
        }

        .team-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(144, 224, 239, 0.3);
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .team-card:hover .team-img {
            border-color: var(--accent);
            transform: scale(1.05);
        }

        .team-social {
            margin-top: 15px;
        }

        .team-social a {
            color: white;
            background: rgba(144, 224, 239, 0.2);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .team-social a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }

        /* Stats */
        .stats-section {
            background: rgba(2, 8, 23, 0.7);
            padding: 80px 0;
            position: relative;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80') no-repeat center center/cover;
            opacity: 0.2;
            z-index: -1;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 10px;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
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
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .carousel-item img {
                height: 400px;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .about-card {
                padding: 20px;
            }
            
            .carousel-caption {
                width: 90%;
                bottom: 20px;
            }
            
            .carousel-caption h5 {
                font-size: 1.2rem;
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
                    <li class="nav-item"><a class="nav-link" href="complaint_status.php">Complaint Status</a></li>
                    <li class="nav-item"><a class="nav-link active" href="aboutus.php">About Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Making Waves in Water Management</h1>
            <p class="hero-subtitle">HydroCity Solutions is revolutionizing urban water infrastructure with innovative technology and sustainable practices to ensure clean, reliable water for generations to come.</p>
        </div>
    </section>

    <!-- About Cards Section -->
    <section class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="about-card">
                    <i class="fas fa-bullseye"></i>
                    <h3>Our Mission</h3>
                    <p>To provide seamless and reliable water supply management solutions for urban areas through cutting-edge technology and sustainable practices.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-card">
                    <i class="fas fa-eye"></i>
                    <h3>Our Vision</h3>
                    <p>To become the global leader in smart water management solutions, creating cities where water scarcity is a thing of the past.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-card">
                    <i class="fas fa-heart"></i>
                    <h3>Our Values</h3>
                    <p>Integrity, innovation, sustainability, and community drive every decision we make and every project we undertake.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Carousel Section -->
    <div class="container">
        <div id="aboutCarousel" class="carousel slide carousel-container" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1566438480900-0609be27a4be?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80" alt="Water Infrastructure">
                    <div class="carousel-caption">
                        <h5>Advanced Water Infrastructure</h5>
                        <p>Building strong and resilient water supply systems for modern cities</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1509316785289-025f5b846b35?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80" alt="Clean Water Initiative">
                    <div class="carousel-caption">
                        <h5>Clean Water Initiatives</h5>
                        <p>Delivering pure and safe water to urban communities worldwide</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1562077772-3bd90403f7f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80" alt="Sustainable Solutions">
                    <div class="carousel-caption">
                        <h5>Sustainable Water Solutions</h5>
                        <p>Implementing eco-friendly technologies for future generations</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-count="250">0</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-count="15">0</div>
                        <div class="stat-label">Cities Served</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-count="98">0</div>
                        <div class="stat-label">Client Satisfaction</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-count="500">0+</div>
                        <div class="stat-label">Specialists</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="container py-5">
        <h2 class="text-center mb-5">Meet Our Leadership</h2>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="team-card">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Team Member" class="team-img">
                    <h4>David Chen</h4>
                    <p class="text-muted">CEO & Founder</p>
                    <p>Visionary leader with 20+ years in water infrastructure</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-card">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Team Member" class="team-img">
                    <h4>Sarah Johnson</h4>
                    <p class="text-muted">CTO</p>
                    <p>Technology innovator specializing in smart water systems</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-card">
                    <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Team Member" class="team-img">
                    <h4>Michael Rodriguez</h4>
                    <p class="text-muted">Head of Operations</p>
                    <p>Ensures seamless execution of all projects</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-card">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Team Member" class="team-img">
                    <h4>Priya Patel</h4>
                    <p class="text-muted">Sustainability Director</p>
                    <p>Champions eco-friendly water solutions</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="container py-5">
        <h2 class="text-center mb-5">Our Notable Projects</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="about-card">
                    <i class="fas fa-project-diagram"></i>
                    <h3>Downtown Water Pipeline</h3>
                    <p>Completed in 2024, this ambitious project involved installing 20km of new water pipelines with smart monitoring technology, serving 500,000 residents.</p>
                    <div class="mt-3">
                        <span class="badge bg-primary me-2">Smart Tech</span>
                        <span class="badge bg-success">Completed</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-card">
                    <i class="fas fa-water"></i>
                    <h3>Uptown Drainage System</h3>
                    <p>Revamped the entire drainage system in Uptown, reducing flood risks by 85% during monsoon seasons while incorporating water recycling features.</p>
                    <div class="mt-3">
                        <span class="badge bg-primary me-2">Flood Control</span>
                        <span class="badge bg-success">Completed</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-card">
                    <i class="fas fa-recycle"></i>
                    <h3>East End Treatment</h3>
                    <p>Enhanced water quality in the East End with our advanced filtration and treatment system, now serving as a model for sustainable urban water solutions.</p>
                    <div class="mt-3">
                        <span class="badge bg-primary me-2">Sustainability</span>
                        <span class="badge bg-warning text-dark">Ongoing</span>
                    </div>
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
                            <li><a href="complaint_status.php">Complaint Status</a></li>
                            <li><a href="aboutus.php">About Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Services</h5>
                        <ul>
                            <li><a href="#">Water Infrastructure</a></li>
                            <li><a href="#">Drainage Systems</a></li>
                            <li><a href="#">Water Treatment</a></li>
                            <li><a href="#">Smart Monitoring</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <h5>Contact</h5>
                        <ul>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Ng Suncity Phase 1 </li>
                            <li><i class="fas fa-phone me-2"></i> +91 9892850696</li>
                            <li><i class="fas fa-envelope me-2"></i> info@hydrocity.com</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright text-center">
                <p>&copy; 2025 HydroCity Solutions. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to perform AI-powered smart search
        function smartSearch(query) {
            // Placeholder for AI search logic
            const results = [];
            const data = ["water", "sewerage", "complaint", "admin", "dashboard"];
        
            data.forEach(item => {
                if (item.includes(query.toLowerCase())) {
                    results.push(item);
                }
            });
        
            return results;
        }
        
        // Function to personalize user experience
        function personalizeContent(userPreferences) {
            // Placeholder for AI personalization logic
            const content = {
                theme: userPreferences.theme || "light",
                language: userPreferences.language || "en"
            };
        
            return content;
        }
        
        // Example usage
        const searchResults = smartSearch("water");
        console.log("Search Results:", searchResults);
        
        const userContent = personalizeContent({theme: "dark", language: "fr"});
        console.log("Personalized Content:", userContent);
    </script>
        // Start animation when stats section is in view
        window.addEventListener('scroll', function() {
            const statsSection = document.querySelector('.stats-section');
            const position = statsSection.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (position < screenPosition) {
                animateStats();
                // Remove event listener after animation starts
                window.removeEventListener('scroll', this);
            }
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>