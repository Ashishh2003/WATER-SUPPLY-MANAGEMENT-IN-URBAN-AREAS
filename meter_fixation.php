<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meter Fixation | HydroCity Solutions</title>
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

        /* Form Container */
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 42, 83, 0.4);
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
        }

        .form-container:hover {
            border-color: rgba(144, 224, 239, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 84, 166, 0.5);
        }

        .form-container::before {
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
            border-radius: 15px;
        }

        .form-container:hover::before {
            opacity: 1;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            padding-bottom: 15px;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--accent), var(--primary));
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--light);
            font-size: 1rem;
        }

        .form-group label.required::after {
            content: " *";
            color: #ff6b6b;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(144, 224, 239, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(144, 224, 239, 0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: all 0.4s cubic-bezier(0.39, 0.575, 0.565, 1);
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.4);
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.6);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .message {
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
        }

        .error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }

        /* Floating Bubbles */
        .bubbles {
            position: fixed;
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
        @media (max-width: 992px) {
            .form-container {
                padding: 30px;
            }
            
            .form-title {
                font-size: 2.2rem;
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
            
            .form-container {
                margin: 30px auto;
                padding: 25px;
            }
            
            .form-title {
                font-size: 2rem;
                margin-bottom: 25px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 20px;
                margin: 20px auto;
            }
            
            .form-title {
                font-size: 1.8rem;
            }
            
            .form-control {
                padding: 10px 12px;
            }
            
            .submit-btn {
                padding: 12px 20px;
                font-size: 1rem;
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

    <main>
        <div class="form-container">
            <h1 class="form-title">Meter Fixation Form</h1>
            
            <?php if (!empty($message)): ?>
                <div class="message <?= $message_class ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="form-grid">
                <div class="form-group">
                    <label for="can" class="required">CAN Number</label>
                    <input type="text" id="can" name="can" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="meterNumber" class="required">Meter Number</label>
                    <input type="text" id="meterNumber" name="meterNumber" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="meterSize" class="required">Meter Size</label>
                    <input type="text" id="meterSize" name="meterSize" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="meterManufactureDate">Meter Manufacture Date</label>
                    <input type="date" id="meterManufactureDate" name="meterManufactureDate" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="makeIssueDate">Make Issue Date</label>
                    <input type="date" id="makeIssueDate" name="makeIssueDate" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="meterFixedDate">Meter Fixed Date</label>
                    <input type="date" id="meterFixedDate" name="meterFixedDate" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="warrantyDate">Warranty Date</label>
                    <input type="date" id="warrantyDate" name="warrantyDate" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="initialReading">Initial Reading (KL)</label>
                    <input type="number" step="0.01" id="initialReading" name="initialReading" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="meterMake">Meter Make</label>
                    <select id="meterMake" name="meterMake" class="form-control">
                        <option value="">Select Meter Make</option>
                        <option value="M/s Bharath Precision Instruments Co">M/s Bharath Precision Instruments Co</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="meterAgency">Meter Agency</label>
                    <select id="meterAgency" name="meterAgency" class="form-control">
                        <option value="">Select Meter Agency</option>
                        <option value="Chambal - NBDN Multijet">Chambal - NBDN Multijet</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </form>
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
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ff6b6b';
                    field.style.boxShadow = '0 0 0 3px rgba(255, 107, 107, 0.2)';
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = this.querySelector('[required]:invalid');
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        });
        
        // Reset field styles when user starts typing
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            });
        });
    </script>
</body>
</html>