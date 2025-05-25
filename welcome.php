<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to HydroCity Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00796b;
            --primary-dark: #004d40;
            --primary-light: #b2dfdb;
            --accent: #00b4d8;
            --white: #ffffff;
            --text-dark: #333333;
            --text-light: #555555;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(0, 77, 64, 0.8) 0%, rgba(0, 42, 83, 0.8) 100%), 
                        url('https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80') no-repeat center center/cover;
            color: var(--white);
            position: relative;
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

        /* Welcome Container */
        .welcome-container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(8px);
            max-width: 450px;
            width: 90%;
            animation: fadeIn 1s ease-in-out;
            position: relative;
            overflow: hidden;
            z-index: 10;
            transition: all 0.4s ease;
        }

        .welcome-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .welcome-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            animation: borderGrow 1s ease-out forwards;
        }

        @keyframes borderGrow {
            0% { width: 0; }
            100% { width: 100%; }
        }

        .welcome-container h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 2.8rem);
            margin-bottom: 15px;
            color: var(--primary);
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome-container p {
            font-size: clamp(1rem, 3vw, 1.2rem);
            color: var(--text-light);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .welcome-container button {
            padding: 14px 35px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 121, 107, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .welcome-container button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 121, 107, 0.6);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .welcome-container button i {
            transition: transform 0.3s ease;
        }

        .welcome-container button:hover i {
            transform: translateX(5px);
        }

        /* Water Drop Animation */
        .water-drop {
            position: absolute;
            width: 15px;
            height: 15px;
            background-color: rgba(0, 180, 216, 0.7);
            border-radius: 50%;
            animation: drop-animation 2s infinite ease-in-out;
            opacity: 0;
            z-index: -1;
            filter: drop-shadow(0 0 5px rgba(0, 180, 216, 0.5));
        }

        @keyframes drop-animation {
            0% {
                transform: translateY(-100px) scale(0.8);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) scale(1.2);
                opacity: 0;
            }
        }

        /* Floating Bubbles */
        .bubble {
            position: absolute;
            bottom: -100px;
            background: rgba(178, 223, 219, 0.2);
            border-radius: 50%;
            animation: rise 15s infinite ease-in;
            z-index: -1;
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

        /* Fade-in Animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-container {
                padding: 30px 20px;
                width: 95%;
            }
            
            .welcome-container h1 {
                font-size: 2.2rem;
            }
            
            .welcome-container button {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .welcome-container {
                padding: 25px 15px;
            }
            
            .welcome-container h1 {
                font-size: 1.8rem;
            }
            
            .welcome-container p {
                font-size: 1rem;
                margin-bottom: 20px;
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
        <div class="bubble" style="width: 40px; height: 40px; left: 10%; animation-duration: 8s;"></div>
        <div class="bubble" style="width: 20px; height: 20px; left: 20%; animation-duration: 5s; animation-delay: 1s;"></div>
        <div class="bubble" style="width: 50px; height: 50px; left: 35%; animation-duration: 7s; animation-delay: 2s;"></div>
        <div class="bubble" style="width: 80px; height: 80px; left: 50%; animation-duration: 11s;"></div>
        <div class="bubble" style="width: 35px; height: 35px; left: 55%; animation-duration: 6s; animation-delay: 1s;"></div>
    </div>

    <!-- Water Drops will be added by JavaScript -->

    <div class="welcome-container">
        <h1>Welcome to HydroCity Solutions</h1>
        <p>Your one-stop solution for all water management needs. Manage your water resources efficiently with our comprehensive platform.</p>
        <button onclick="window.location.href='login1.php'">
            Proceed to Login
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <script>
        // Create water drops
        const createWaterDrops = () => {
            const container = document.body;
            const numberOfDrops = 30;
            
            for (let i = 0; i < numberOfDrops; i++) {
                const drop = document.createElement('div');
                drop.className = 'water-drop';
                drop.style.left = Math.random() * window.innerWidth + 'px';
                drop.style.animationDelay = Math.random() * 2 + 's';
                container.appendChild(drop);
                
                // Recycle drops for better performance
                drop.addEventListener('animationend', () => {
                    drop.remove();
                    const newDrop = document.createElement('div');
                    newDrop.className = 'water-drop';
                    newDrop.style.left = Math.random() * window.innerWidth + 'px';
                    newDrop.style.animationDelay = Math.random() * 2 + 's';
                    container.appendChild(newDrop);
                });
            }
        };

        // Ripple effect for button
        const loginButton = document.querySelector('.welcome-container button');
        loginButton.addEventListener('click', function(e) {
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

        // Initialize animations
        window.addEventListener('DOMContentLoaded', () => {
            createWaterDrops();
        });
    </script>
</body>
</html>