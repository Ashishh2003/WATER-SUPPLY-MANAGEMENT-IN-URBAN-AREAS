<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HydroCity Solutions - Water Management</title>
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

        html, body {
            height: 100%;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, rgba(0, 11, 26, 0.9) 0%, rgba(0, 42, 83, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80') no-repeat center center/cover;
            color: #fff;
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

        /* Header & Logo */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            position: relative;
            z-index: 1000;
        }

        .logo {
            height: 60px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            filter: drop-shadow(0 0 10px rgba(144, 224, 239, 0.5));
        }

        .logo:hover {
            transform: scale(1.1) rotate(-5deg);
            filter: drop-shadow(0 0 15px rgba(144, 224, 239, 0.8));
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

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .navbar button {
            background: transparent;
            border: none;
            color: var(--light);
            padding: 22px 25px;
            margin: 0 5px;
            font-size: 1.05rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar button::before {
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

        .navbar button:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .navbar button:hover {
            color: white;
            text-shadow: 0 0 10px rgba(144, 224, 239, 0.7);
        }

        .navbar button i {
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .navbar button:hover i {
            transform: scale(1.2);
            color: var(--accent);
        }

        .admin-button {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 30px;
            padding: 12px 25px;
            margin-left: 15px;
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.4);
            transition: all 0.4s cubic-bezier(0.39, 0.575, 0.565, 1);
            position: relative;
            overflow: hidden;
        }

        .admin-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .admin-button:hover::before {
            left: 100%;
        }

        .admin-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.6);
        }

        /* Main Content */
        main {
            padding: 60px 5% 100px;
            position: relative;
            z-index: 1;
        }

        .dashboard h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
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

        .dashboard h1::after {
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

        .buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .large-btn {
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            padding: 30px;
            color: white;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            box-shadow: 0 8px 32px rgba(0, 42, 83, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .large-btn::before {
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

        .large-btn:hover::before {
            opacity: 1;
        }

        .large-btn:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 40px rgba(0, 84, 166, 0.4);
            border-color: rgba(144, 224, 239, 0.4);
        }

        .large-btn i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--accent);
            transition: all 0.4s ease;
        }

        .large-btn:hover i {
            transform: scale(1.2) rotate(10deg);
            text-shadow: 0 0 20px rgba(144, 224, 239, 0.7);
        }

        .large-btn span {
            font-size: 1.3rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .large-btn:hover span {
            letter-spacing: 1px;
        }

        .large-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--primary));
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.5s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .large-btn:hover::after {
            transform: scaleX(1);
            transform-origin: left;
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

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--secondary), var(--dark));
            padding: 30px 5%;
            text-align: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 -5px 20px rgba(0, 42, 83, 0.3);
        }

        footer p {
            font-size: 1rem;
            opacity: 0.8;
            letter-spacing: 0.5px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard h1 {
                font-size: 3rem;
            }
            
            .large-btn {
                min-height: 180px;
            }
        }

        @media (max-width: 992px) {
            .buttons {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .large-btn i {
                font-size: 2.5rem;
            }
            
            .large-btn span {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 15px;
            }
            
            .logo {
                margin-bottom: 15px;
            }
            
            .navbar {
                padding: 0;
                border-radius: 0;
            }
            
            .nav-links {
                flex-direction: column;
                width: 100%;
                display: none;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .navbar button {
                width: 100%;
                text-align: center;
                padding: 18px;
                justify-content: center;
                border-bottom: 1px solid rgba(144, 224, 239, 0.1);
            }
            
            .admin-button {
                margin: 10px 0;
                width: 100%;
                justify-content: center;
            }
            
            .menu-toggle {
                display: block;
                background: transparent;
                border: none;
                color: white;
                font-size: 1.8rem;
                cursor: pointer;
                padding: 10px;
                position: absolute;
                right: 20px;
                top: 20px;
            }
            
            .dashboard h1 {
                font-size: 2.5rem;
                margin-bottom: 30px;
            }
            
            .buttons {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .dashboard h1 {
                font-size: 2rem;
            }
            
            .buttons {
                grid-template-columns: 1fr;
            }
            
            .large-btn {
                min-height: 150px;
                padding: 25px;
            }
            
            .large-btn i {
                font-size: 2rem;
            }
            
            .large-btn span {
                font-size: 1.1rem;
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

    <header>
        <img src="logo.png" alt="HydroCity Solutions Logo" class="logo">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <nav class="navbar">
        <div class="navbar-container">
            <ul class="nav-links" id="navLinks">
                <li><button onclick="window.location.href='home.php'"><i class="fas fa-home"></i> Home</button></li>
                <li><button onclick="window.location.href='complaint_status.php'"><i class="fas fa-clipboard-list"></i> Complaint Status</button></li>
                <li><button onclick="window.location.href='aboutus.php'"><i class="fas fa-info-circle"></i> About Us</button></li>
                <li><button class="admin-button" onclick="window.location.href='admin.php'"><i class="fas fa-lock"></i> Admin Login</button></li>
            </ul>
        </div>
    </nav>

    <main class="dashboard">
        <h1>HydroCity Solutions</h1>
        <div class="buttons">
            <button onclick="window.location.href='drainage.php'" class="large-btn">
                <i class="fas fa-water"></i>
                <span>Drainage Complaint</span>
            </button>
            <button onclick="window.location.href='sewerage.php'" class="large-btn">
                <i class="fas fa-toilet"></i>
                <span>Sewerage</span>
            </button>
            <button onclick="window.location.href='illegal_connection.php'" class="large-btn">
                <i class="fas fa-ban"></i>
                <span>Illegal Connection</span>
            </button>
            <button onclick="window.location.href='newwaterconnection.php'" class="large-btn">
                <i class="fas fa-faucet"></i>
                <span>New Water Connection</span>
            </button>
            <button onclick="window.location.href='meter_fixation.php'" class="large-btn">
                <i class="fas fa-tachometer-alt"></i>
                <span>Meter Fixation</span>
            </button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 HydroCity Solutions | All rights reserved</p>
    </footer>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuToggle.innerHTML = navLinks.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
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
        
        // Ripple effect for buttons
        const buttons = document.querySelectorAll('.large-btn, .admin-button');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const x = e.clientX - e.target.getBoundingClientRect().left;
                const y = e.clientY - e.target.getBoundingClientRect().top;
                
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 1000);
            });
        });
    </script>
</body>
</html>