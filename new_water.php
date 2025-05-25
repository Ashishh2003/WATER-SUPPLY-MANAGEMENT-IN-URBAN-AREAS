<?php
// Database connection - Using default XAMPP credentials
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password (empty)
$dbname = "water_monitoring";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . 
        "<br>Please make sure:<br>
        1. XAMPP MySQL is running<br>
        2. You're using the correct credentials");
}

// Create database if it doesn't exist
if (!$conn->query("CREATE DATABASE IF NOT EXISTS $dbname")) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create water data table with proper structure
$createTable = $conn->query("CREATE TABLE IF NOT EXISTS water_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    time TIME NOT NULL,
    total_consumption_mld FLOAT NOT NULL COMMENT 'Million Liters per Day',
    reservoir_level_percentage FLOAT NOT NULL,
    demand_supply_gap FLOAT NOT NULL,
    lake_levels TEXT NOT NULL COMMENT 'JSON of individual lake levels',
    water_cuts TEXT COMMENT 'Areas with water cuts',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (date, time)
");

if (!$createTable) {
    die("Error creating table: " . $conn->error);
}

// Function to fetch real-time data (mock function)
function fetchLiveWaterData() {
    $water_cuts = rand(0, 1) ? ['Andheri East', 'Dahisar', 'Kurla'] : [];
    return [
        'date' => date('Y-m-d'),
        'time' => date('H:i:s'),
        'total_consumption_mld' => rand(4000, 4600),
        'reservoir_level_percentage' => rand(65, 85) + (rand(0, 9) / 10,
        'demand_supply_gap' => rand(5, 20) + (rand(0, 9) / 10,
        'lake_levels' => json_encode([
            'Bhatsa' => rand(60, 90),
            'Upper Vaitarna' => rand(65, 95),
            'Middle Vaitarna' => rand(55, 85),
            'Tansa' => rand(70, 100),
            'Modak Sagar' => rand(60, 90),
            'Vihar' => rand(50, 80),
            'Tulsi' => rand(40, 70)
        ]),
        'water_cuts' => json_encode($water_cuts)
    ];
}

// Check and insert current data
$currentHour = date('Y-m-d H:00:00');
$hasCurrentData = $conn->query("SELECT 1 FROM water_data WHERE CONCAT(date, ' ', time) >= '$currentHour'")->num_rows > 0;

if (!$hasCurrentData) {
    $liveData = fetchLiveWaterData();
    $stmt = $conn->prepare("INSERT INTO water_data (date, time, total_consumption_mld, reservoir_level_percentage, demand_supply_gap, lake_levels, water_cuts) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddsss", 
        $liveData['date'],
        $liveData['time'],
        $liveData['total_consumption_mld'],
        $liveData['reservoir_level_percentage'],
        $liveData['demand_supply_gap'],
        $liveData['lake_levels'],
        $liveData['water_cuts']
    );
    $stmt->execute();
}

// Fetch data for display
$waterData = $conn->query("SELECT * FROM water_data ORDER BY date DESC, time DESC LIMIT 1")->fetch_assoc();
$dailyData = $conn->query("SELECT date, AVG(total_consumption_mld) as avg_consumption, AVG(reservoir_level_percentage) as avg_reservoir_level FROM water_data GROUP BY date ORDER BY date DESC LIMIT 30")->fetch_all(MYSQLI_ASSOC);
$hourlyData = $conn->query("SELECT time, total_consumption_mld, reservoir_level_percentage FROM water_data WHERE date = CURDATE() ORDER BY time DESC LIMIT 24")->fetch_all(MYSQLI_ASSOC);

// Calculate trends
$consumptionTrend = 0;
$reservoirTrend = 0;
if (count($dailyData) > 1) {
    $latest = $dailyData[0];
    $previous = $dailyData[1];
    $consumptionTrend = (($latest['avg_consumption'] - $previous['avg_consumption']) / $previous['avg_consumption'] * 100);
    $reservoirTrend = (($latest['avg_reservoir_level'] - $previous['avg_reservoir_level']) / $previous['avg_reservoir_level'] * 100);
}

// Parse JSON data
$lakeLevels = json_decode($waterData['lake_levels'] ?? '{}', true);
$waterCuts = json_decode($waterData['water_cuts'] ?? '[]', true);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mumbai Water Supply Monitoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #00b4d8;
            --primary-dark: #0077b6;
            --accent: #90e0ef;
            --success: #4CAF50;
            --warning: #FFC107;
            --danger: #F44336;
            --dark: #03045e;
            --light: #caf0f8;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #000b1a 0%, #002a53 100%);
            color: white;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .navbar {
            background: rgba(0, 11, 26, 0.9);
            padding: 20px 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }
        .navbar-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
        }
        .navbar-title i {
            color: var(--accent);
            margin-right: 12px;
            font-size: 1.5rem;
        }
        .container {
            padding: 30px 5%;
            max-width: 1400px;
            margin: 0 auto;
        }
        .dashboard-title {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        .dashboard-title h1 {
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--light);
            margin-bottom: 10px;
        }
        .dashboard-title p {
            opacity: 0.8;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .water-data-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .data-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(5px);
        }
        .data-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.12);
        }
        .data-card h2 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--accent);
            display: flex;
            align-items: center;
        }
        .data-card h2 i {
            margin-right: 12px;
            font-size: 1.3rem;
        }
        .data-value {
            font-size: 2.4rem;
            font-weight: 600;
            margin-bottom: 5px;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .data-label {
            opacity: 0.8;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }
        .trend {
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .trend.up {
            color: var(--danger);
        }
        .trend.down {
            color: var(--success);
        }
        .trend i {
            margin-right: 8px;
        }
        .chart-container {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
        }
        .chart-container h2 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--accent);
            display: flex;
            align-items: center;
        }
        .chart-container h2 i {
            margin-right: 12px;
        }
        .chart-wrapper {
            position: relative;
            height: 350px;
            width: 100%;
        }
        .last-updated {
            text-align: center;
            opacity: 0.7;
            font-size: 0.9rem;
            margin-top: 40px;
        }
        .alert-container {
            margin-bottom: 30px;
        }
        .alert {
            background: rgba(255, 193, 7, 0.15);
            border-left: 4px solid var(--warning);
            padding: 20px;
            border-radius: 4px;
        }
        .alert h2 {
            color: var(--warning);
            margin-top: 0;
            display: flex;
            align-items: center;
        }
        .alert h2 i {
            margin-right: 10px;
        }
        .alert ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        @media (max-width: 768px) {
            .navbar-title { font-size: 1.5rem; }
            .dashboard-title h1 { font-size: 1.8rem; }
            .data-card { padding: 20px; }
            .data-value { font-size: 2rem; }
            .chart-wrapper { height: 250px; }
        }
        @media (max-width: 480px) {
            .water-data-container { grid-template-columns: 1fr; }
            .navbar-title { font-size: 1.3rem; }
            .dashboard-title h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">
            <i class="fas fa-tint"></i>
            <span>Mumbai Water Supply Dashboard</span>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-title">
            <h1>Real-Time Water Monitoring</h1>
            <p>Current status of Mumbai's water supply system with consumption trends and reservoir levels</p>
        </div>
        
        <div class="water-data-container">
            <div class="data-card">
                <h2><i class="fas fa-water"></i> Current Consumption</h2>
                <div class="data-value"><?php echo number_format($waterData['total_consumption_mld'] ?? 0); ?> MLD</div>
                <div class="data-label">Million Liters per Day</div>
                <div class="trend <?php echo $consumptionTrend >= 0 ? 'up' : 'down'; ?>">
                    <i class="fas fa-arrow-<?php echo $consumptionTrend >= 0 ? 'up' : 'down'; ?>"></i>
                    <?php echo abs(round($consumptionTrend, 1)); ?>% from yesterday
                </div>
            </div>
            
            <div class="data-card">
                <h2><i class="fas fa-reservoir"></i> Reservoir Levels</h2>
                <div class="data-value"><?php echo number_format($waterData['reservoir_level_percentage'] ?? 0, 1); ?>%</div>
                <div class="data-label">of total capacity</div>
                <div class="trend <?php echo $reservoirTrend >= 0 ? 'up' : 'down'; ?>">
                    <i class="fas fa-arrow-<?php echo $reservoirTrend >= 0 ? 'up' : 'down'; ?>"></i>
                    <?php echo abs(round($reservoirTrend, 1)); ?>% change
                </div>
            </div>
            
            <div class="data-card">
                <h2><i class="fas fa-tint-slash"></i> Supply Gap</h2>
                <div class="data-value"><?php echo number_format($waterData['demand_supply_gap'] ?? 0, 1); ?>%</div>
                <div class="data-label">Demand vs Supply Difference</div>
                <div class="trend <?php echo ($waterData['demand_supply_gap'] ?? 0) > 15 ? 'up' : 'down'; ?>">
                    <?php echo ($waterData['demand_supply_gap'] ?? 0) > 15 ? 'High shortage' : 'Within normal range'; ?>
                </div>
            </div>
            
            <div class="data-card">
                <h2><i class="fas fa-hourglass-half"></i> Hourly Supply</h2>
                <div class="data-value"><?php echo round(($waterData['total_consumption_mld'] ?? 0) / 24, 1); ?> MLH</div>
                <div class="data-label">Million Liters per Hour</div>
                <div class="trend">
                    ~<?php echo round(($waterData['total_consumption_mld'] ?? 0) / 20); ?> liters per capita
                </div>
            </div>
        </div>
        
        <?php if (!empty($waterCuts)): ?>
        <div class="alert-container">
            <div class="alert">
                <h2><i class="fas fa-exclamation-triangle"></i> Water Cuts in Effect</h2>
                <p>The following areas are currently experiencing water supply cuts:</p>
                <ul>
                    <?php foreach ($waterCuts as $area): ?>
                        <li><?php echo htmlspecialchars($area); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="chart-container">
            <h2><i class="fas fa-chart-line"></i> Weekly Consumption Trend</h2>
            <div class="chart-wrapper">
                <canvas id="consumptionChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <h2><i class="fas fa-chart-area"></i> Reservoir Levels</h2>
            <div class="chart-wrapper">
                <canvas id="reservoirChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <h2><i class="fas fa-clock"></i> Hourly Consumption Today</h2>
            <div class="chart-wrapper">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <h2><i class="fas fa-lake"></i> Reservoir Levels Breakdown</h2>
            <div class="chart-wrapper">
                <canvas id="lakeChart"></canvas>
            </div>
        </div>
        
        <div class="last-updated">
            Last updated: <?php echo date('M j, Y \a\t g:i A'); ?>
        </div>
    </div>

    <script>
        // Prepare data for charts
        const dailyData = <?php echo json_encode(array_reverse($dailyData)); ?>;
        const hourlyData = <?php echo json_encode(array_reverse($hourlyData)); ?>;
        const lakeLevels = <?php echo json_encode($lakeLevels); ?>;
        
        // Consumption Chart
        const consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
        new Chart(consumptionCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'Water Consumption (MLD)',
                    data: dailyData.map(item => item.avg_consumption),
                    borderColor: '#00b4d8',
                    backgroundColor: 'rgba(0, 180, 216, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: {
                        grid: { display: false, color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                    }
                }
            }
        });

        // Reservoir Chart
        const reservoirCtx = document.getElementById('reservoirChart').getContext('2d');
        new Chart(reservoirCtx, {
            type: 'bar',
            data: {
                labels: dailyData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'Reservoir Level (%)',
                    data: dailyData.map(item => item.avg_reservoir_level),
                    backgroundColor: 'rgba(144, 224, 239, 0.7)',
                    borderColor: 'rgba(144, 224, 239, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: 'rgba(255, 255, 255, 0.7)' }
                    }
                }
            }
        });

        // Hourly Consumption Chart
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: hourlyData.map(item => new Date('1970-01-01 ' + item.time).toLocaleTimeString('en-US', { hour: 'numeric', hour12: true })),
                datasets: [{
                    label: 'Hourly Consumption (MLD)',
                    data: hourlyData.map(item => item.total_consumption_mld),
                    borderColor: '#90e0ef',
                    backgroundColor: 'rgba(144, 224, 239, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: 'rgba(255, 255, 255, 0.1)' } }
                }
            }
        });

        // Lake Levels Chart
        const lakeCtx = document.getElementById('lakeChart').getContext('2d');
        new Chart(lakeCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(lakeLevels),
                datasets: [{
                    label: 'Fill Level (%)',
                    data: Object.values(lakeLevels),
                    backgroundColor: [
                        'rgba(0, 180, 216, 0.7)',
                        'rgba(0, 119, 182, 0.7)',
                        'rgba(3, 4, 94, 0.7)',
                        'rgba(72, 202, 228, 0.7)',
                        'rgba(144, 224, 239, 0.7)',
                        'rgba(202, 240, 248, 0.7)'
                    ],
                    borderColor: 'rgba(255, 255, 255, 0.3)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        min: 0,
                        max: 100,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    }
                }
            }
        });

        // Auto-refresh every 15 minutes
        setTimeout(() => {
            window.location.reload();
        }, 15 * 60 * 1000);
    </script>
</body>
</html>