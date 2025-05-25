<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HydroCity Solutions - User Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
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

        /* Registration Container */
        .container {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            z-index: 10;
            transform: scale(1);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 180, 216, 0.1) 0%, rgba(0, 119, 182, 0.2) 100%);
            z-index: -1;
        }

        .container:hover {
            transform: scale(1.02);
            box-shadow: 0 30px 50px rgba(0, 84, 166, 0.4);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2rem;
            font-weight: 600;
            color: white;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--accent), var(--primary));
            border-radius: 3px;
        }

        /* Form Elements */
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 1.1rem;
        }

        input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            font-size: 1rem;
            color: white;
            outline: none;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 10px rgba(144, 224, 239, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 180, 216, 0.4);
            position: relative;
            overflow: hidden;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 180, 216, 0.6);
        }

        button:active {
            transform: translateY(0);
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            min-height: 24px;
            color: #ff6b6b;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .strength-meter {
            height: 5px;
            background: rgba(255, 255, 255, 0.1);
            margin-top: 5px;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .strength-meter::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: #ff6b6b;
            transition: width 0.3s ease, background 0.3s ease;
        }

        .strength-meter[data-strength="1"]::after {
            width: 25%;
            background: #ff6b6b;
        }
        .strength-meter[data-strength="2"]::after {
            width: 50%;
            background: #ffb347;
        }
        .strength-meter[data-strength="3"]::after {
            width: 75%;
            background: #f6e58d;
        }
        .strength-meter[data-strength="4"]::after {
            width: 100%;
            background: #7bed9f;
        }

        .password-hint {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 5px;
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .container {
                padding: 30px 20px;
                margin: 20px;
                width: calc(100% - 40px);
            }

            h2 {
                font-size: 1.8rem;
            }

            input {
                padding: 12px 12px 12px 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Water Wave Animation -->
    <div class="water-wave"></div>

    <div class="container">
        <h2>Create Your Account</h2>
        <form id="registrationForm" method="POST" action="">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Full Name" required 
                       oninput="this.value = this.value.replace(/^\s+|\s+$/g, '')" />
            </div>
            
            <div class="input-group">
                <i class="fas fa-at"></i>
                <input type="text" name="username" id="regUsername" placeholder="Username" required 
                       oninput="this.value = this.value.replace(/\s/g, '')" />
            </div>
            
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="newPassword" placeholder="New Password" required 
                       oninput="updatePasswordStrength(); this.value = this.value.replace(/\s/g, '')" />
                <div class="strength-meter" id="strengthMeter" data-strength="0"></div>
                <div class="password-hint" id="passwordHint">
                    Password should be at least 8 characters with a mix of letters, numbers, and symbols
                </div>
            </div>
            
            <button type="submit">Register Now</button>
        </form>

        <div class="message" id="message"></div>
    </div>

    <script>
        // Password strength indicator
        function updatePasswordStrength() {
            const password = document.getElementById('newPassword').value;
            const meter = document.getElementById('strengthMeter');
            const hint = document.getElementById('passwordHint');
            
            // Reset
            let strength = 0;
            
            // Length check
            if (password.length > 0) hint.style.display = 'block';
            else hint.style.display = 'none';
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Contains numbers
            if (/\d/.test(password)) strength++;
            
            // Contains special chars
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
            
            // Contains both lowercase and uppercase
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            
            // Cap at 4
            strength = Math.min(strength, 4);
            
            // Update meter
            meter.setAttribute('data-strength', strength);
        }

        // Add focus effects to inputs
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i').style.color = 'white';
                this.parentElement.querySelector('i').style.transform = 'translateY(-50%) scale(1.2)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('i').style.color = 'var(--accent)';
                this.parentElement.querySelector('i').style.transform = 'translateY(-50%)';
            });
        });

        // Form submission handling
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const button = this.querySelector('button');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
            button.disabled = true;
            
            // Simulate processing (in a real app, this would be your AJAX call)
            setTimeout(() => {
                // Submit the form programmatically
                this.submit();
            }, 1500);
        });

        // Show password hint on focus
        document.getElementById('newPassword').addEventListener('focus', function() {
            document.getElementById('passwordHint').style.display = 'block';
        });
    </script>
</body>
</html>