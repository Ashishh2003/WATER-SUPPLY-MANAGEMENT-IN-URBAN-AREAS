<?php
include 'db.php';

$sql = "SELECT id, landmark, image_path, status FROM sewerage";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewerage Complaints | HydroCity Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00b4d8;
            --secondary: #0077b6;
            --accent: #90e0ef;
            --dark: #03045e;
            --light: #caf0f8;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --gray: #6c757d;
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
            padding: 40px 5% 100px;
            position: relative;
            z-index: 1;
        }

        .dashboard-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            background: linear-gradient(to right, var(--light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }

        .dashboard-title::after {
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

        /* Complaints Table */
        .complaints-container {
            background: rgba(2, 62, 125, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(144, 224, 239, 0.2);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 42, 83, 0.4);
            overflow-x: auto;
        }

        .complaints-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 600px;
        }

        .complaints-table thead th {
            background: rgba(0, 119, 182, 0.5);
            color: white;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .complaints-table tbody tr {
            transition: all 0.3s ease;
        }

        .complaints-table tbody tr:nth-child(even) {
            background: rgba(0, 119, 182, 0.1);
        }

        .complaints-table tbody tr:nth-child(odd) {
            background: rgba(0, 42, 83, 0.1);
        }

        .complaints-table tbody tr:hover {
            background: rgba(0, 180, 216, 0.2);
        }

        .complaints-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(144, 224, 239, 0.1);
            vertical-align: middle;
        }

        .complaint-image {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .complaint-image:hover {
            transform: scale(1.8);
            z-index: 100;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: var(--warning);
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-completed {
            background-color: rgba(40, 167, 69, 0.2);
            color: var(--success);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .complete-btn {
            background: linear-gradient(135deg, var(--success), #1e7e34);
            color: white;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .complete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
        }

        .completed-btn {
            background: rgba(108, 117, 125, 0.2);
            color: var(--light);
            cursor: not-allowed;
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
        @media (max-width: 1200px) {
            .dashboard-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 992px) {
            .complaints-container {
                padding: 20px;
            }
            
            .complaints-table thead th,
            .complaints-table td {
                padding: 12px;
            }
            
            .complaint-image {
                width: 100px;
                height: 75px;
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
            
            .dashboard-title {
                font-size: 2rem;
                margin-bottom: 25px;
            }
            
            .complaints-container {
                padding: 15px;
            }
            
            .complaint-image {
                width: 80px;
                height: 60px;
            }
            
            .action-btn {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 1.8rem;
                padding-bottom: 10px;
            }
            
            .dashboard-title::after {
                width: 100px;
                height: 3px;
            }
            
            .complaints-container {
                padding: 10px;
            }
            
            .complaints-table thead th,
            .complaints-table td {
                padding: 10px;
                font-size: 0.9rem;
            }
            
            .complaint-image {
                width: 60px;
                height: 45px;
            }
            
            .status-badge {
                padding: 4px 8px;
                font-size: 0.8rem;
            }
            
            .action-btn {
                padding: 5px 10px;
                font-size: 0.8rem;
                gap: 5px;
            }
        }

        /* Modal for enlarged image */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            overflow: auto;
        }

        .modal-content {
            display: block;
            margin: auto;
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 8px;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .close:hover {
            color: var(--accent);
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
                <li class="nav-item"><a class="nav-link" href="complaint_status.php">Complaint Status</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <h1 class="dashboard-title">Sewerage Complaints</h1>
        
        <div class="complaints-container">
            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Landmark</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["landmark"]); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                 alt="Complaint Image" 
                                 class="complaint-image"
                                 onclick="openModal('<?php echo htmlspecialchars($row['image_path']); ?>')">
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo htmlspecialchars($row['status']); ?>">
                                <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <button class="action-btn complete-btn" 
                                        onclick="markCompleted(<?php echo $row['id']; ?>, this)">
                                    <i class="fas fa-check-circle"></i> Complete
                                </button>
                            <?php else: ?>
                                <button class="action-btn completed-btn" disabled>
                                    <i class="fas fa-check"></i> Completed
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <!-- Image Modal -->
        <div id="imageModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImage">
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
        
        // Image modal functionality
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = "block";
            modalImg.src = imageSrc;
        }
        
        function closeModal() {
            document.getElementById('imageModal').style.display = "none";
        }
        
        // Close modal when clicking outside the image
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        // Mark complaint as completed
        function markCompleted(id, button) {
            if (confirm('Are you sure you want to mark this complaint as completed?')) {
                // Create form data
                const formData = new FormData();
                formData.append('complaint_id', id);
                formData.append('table', 'sewerage');
                
                // Show loading state
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                button.disabled = true;
                
                fetch('update_status.php', {
                    method: 'POST',
                    body: formData
                })
                .then(async response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        throw new Error(`Expected JSON, got: ${text}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.error || 'Unknown error');
                    }
                    // Update UI
                    const row = button.closest('tr');
                    row.querySelector('.status-badge').className = 'status-badge status-completed';
                    row.querySelector('.status-badge').textContent = 'Completed';
                    
                    // Replace button
                    button.outerHTML = `
                        <button class="action-btn completed-btn" disabled>
                            <i class="fas fa-check"></i> Completed
                        </button>
                    `;
                    
                    // Show success notification
                    showNotification('Complaint marked as completed successfully!', 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error: ' + error.message, 'error');
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        }
        
        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            
            notification.style.position = 'fixed';
            notification.style.bottom = '20px';
            notification.style.right = '20px';
            notification.style.padding = '15px 25px';
            notification.style.borderRadius = '8px';
            notification.style.color = 'white';
            notification.style.fontWeight = '500';
            notification.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
            notification.style.zIndex = '1000';
            notification.style.animation = 'fadeIn 0.3s ease';
            
            if (type === 'success') {
                notification.style.background = 'linear-gradient(135deg, var(--success), #1e7e34)';
            } else {
                notification.style.background = 'linear-gradient(135deg, var(--danger), #c82333)';
            }
            
            document.body.appendChild(notification);
            
            // Remove notification after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'fadeOut 0.5s ease';
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>