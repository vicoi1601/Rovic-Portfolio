<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ppa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Field labels array (same as original)
$fieldLabels = array(
    "owc" => "ORAL AND WRITTEN COMMUNICATION",
    "owc_pl" => "PROFICIENCY LEVEL",
    "dse" => "DELIVERING SERVICE EXCELLENCE",
    "dse_pl" => "PROFICIENCY LEVEL",
    "exi" => "EXEMPLIFYING INTEGRITY",
    "exi_pl" => "PROFICIENCY LEVEL",
    "tsc" => "THINKING STRATEGICALLY AND CREATIVELY",
    "tsc_pl" => "PROFICIENCY LEVEL",
    "lc" => "LEADING CHANGE",
    "lc_pl" => "PROFICIENCY LEVEL",
    "collaborative" => "COLLABORATIVE, INCLUSIVE, WORKING",
    "collaborative_pl" => "PROFICIENCY LEVEL",
    "performance" => "PERFORMANCE AND COACHING FOR RESULTS",
    "performance_pl" => "PROFICIENCY LEVEL",
    "nurturing" => "NURTURING A HIGH PERFORMING ORGANIZATION",
    "nurturing_pl" => "PROFICIENCY LEVEL",
    "administrative" => "ADMINISTRATIVE SERVICES MANAGEMENT",
    "administrative_pl" => "PROFICIENCY LEVEL",
    // Add all your other fields here...
);

$levelMapping = array(1 => 'Basic', 2 => 'Intermediate', 3 => 'Advance', 4 => 'Superior');
$counts = array('Basic' => 0, 'Intermediate' => 0, 'Advance' => 0, 'Superior' => 0);

$totalMetCount = 0;
$totalNotMetCount = 0;
$total = 0;
$percentageMet = 0;
$percentageNotMet = 0;
$selectedFieldLabel = "";
$includePL = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedField = $_POST["field_select"];
    $selectedFieldLabel = $fieldLabels[$selectedField];
    $includePL = isset($fieldLabels[$selectedField . "_pl"]);
    
    $sql = "SELECT `ppa_id`, `plant_pos`, `surname`, `first_name`, `middle_name`, `designated_office`, `gender`, `status`";
    if ($includePL) {
        $sql .= ", `$selectedField" . "_pl` AS `Proficiency Level`";
    }
    $sql .= " FROM `gaps` WHERE `$selectedField` = 'NOT MET'";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    
    // Count query
    $sqlCount = "SELECT 
                SUM(CASE WHEN `$selectedField` = 'MET' THEN 1 ELSE 0 END) AS met_count,
                SUM(CASE WHEN `$selectedField` = 'NOT MET' THEN 1 ELSE 0 END) AS not_met_count,
                COUNT(*) AS total_count
             FROM `gaps`";
    
    $resultCount = $conn->query($sqlCount);
    
    if ($resultCount) {
        $row = $resultCount->fetch_assoc();
        $totalMetCount = $row['met_count'];
        $totalNotMetCount = $row['not_met_count'];
        $total = $totalMetCount + $totalNotMetCount;
        
        if ($totalNotMetCount == 0) {
            $percentageMet = 100;
            $percentageNotMet = 0;
        } else {
            $percentageMet = round(($totalMetCount / $total) * 100, 0);
            $percentageNotMet = round(($totalNotMetCount / $total) * 100, 0);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPA Competency Gaps Analysis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
    background: none;
    background-color: #ffffff; /* or transparent */
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    padding: 20px;
}


        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
           background: linear-gradient(135deg, #20354dff, #1976d2);
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .form-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .form-group select {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .results-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .results-header h3 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 16px;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #475569;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .action-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .summary-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #3b82f6;
        }

        .summary-card.status {
            border-left-color: #10b981;
        }

        .summary-card h4 {
            color: #ef4444;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-card.status h4 {
            color: #10b981;
        }

        .summary-list {
            list-style: none;
        }

        .summary-item {
            padding: 8px 0;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item strong {
            color: #1e293b;
        }

        .total-count {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
        }

        .charts-section {
            margin-top: 50px;
            padding-top: 40px;
            border-top: 3px solid #e2e8f0;
        }

        .charts-section h3 {
            text-align: center;
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 40px;
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: 450px;
        }

        .chart-container canvas {
            max-height: 350px !important;
        }

        .chart-title {
            text-align: center;
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .no-results i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .form-container,
            .button-group,
            .action-btn,
            .charts-section {
                display: none !important;
            }
            .container {
                box-shadow: none;
            }
            table td:last-child,
            table th:last-child {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            .button-group {
                flex-direction: column;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
            .summary-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> PPA Competency Based Monitoring System</h1>
            <p>Gaps Analysis & Personnel Performance Tracking</p>
        </div>

        <div class="content">
            <div class="form-container">
                <form method="post">
                    <div class="form-group">
                        <label for="field_select"><i class="fas fa-search"></i> Select Competency to Analyze</label>
                        <select id="field_select" name="field_select" required>
                            <option value="">-- Choose a competency --</option>
                            <?php foreach ($fieldLabels as $key => $value): ?>
                                <?php if (!strpos($key, "_pl")): ?>
                                    <option value="<?php echo $key; ?>" <?php echo (isset($selectedField) && $selectedField == $key) ? 'selected' : ''; ?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Analyze Gaps
                        </button>
                        <button type="button" class="btn btn-warning" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Report
                        </button>
                    </div>
                </form>
            </div>

            <?php if (isset($result) && $result->num_rows > 0): ?>
                <div class="results-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Personnel with Competency Gaps - <?php echo $selectedFieldLabel; ?> (October 2025)</h3>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>PPA ID</th>
                                <th>Position</th>
                                <th>Surname</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Office</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <?php if ($includePL): ?>
                                    <th>PL</th>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr id="row<?php echo $row['ppa_id']; ?>">
                                    <td><?php echo htmlspecialchars($row['ppa_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['plant_pos']); ?></td>
                                    <td><?php echo htmlspecialchars($row['surname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['designated_office']); ?></td>
                                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <?php if ($includePL && isset($row['Proficiency Level'])): ?>
                                        <?php
                                            $plValue = $row['Proficiency Level'];
                                            if ($plValue == 1) $counts['Basic']++;
                                            elseif ($plValue == 2) $counts['Intermediate']++;
                                            elseif ($plValue == 3) $counts['Advance']++;
                                            elseif ($plValue == 4) $counts['Superior']++;
                                        ?>
                                        <td><span style="background: #dbeafe; padding: 4px 12px; border-radius: 20px; font-weight: 600; color: #1e40af;"><?php echo $plValue; ?></span></td>
                                        <td>
                                            <button class="action-btn" onclick="removeRow('row<?php echo $row['ppa_id']; ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="summary-section">
                    <?php if ($includePL): ?>
                    <div class="summary-card">
                        <h4><i class="fas fa-layer-group"></i> Proficiency Level Summary</h4>
                        <ul class="summary-list">
                            <?php
                            $totalRecords = array_sum($counts);
                            foreach ($counts as $level => $count):
                                $percentage = ($totalRecords !== 0) ? number_format(($count / $totalRecords) * 100, 0) : 0;
                            ?>
                                <li class="summary-item">
                                    <strong><?php echo $level; ?>:</strong>
                                    <span><?php echo $count; ?> (<?php echo $percentage; ?>%)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="summary-card status">
                        <h4><i class="fas fa-chart-bar"></i> Competency Status</h4>
                        <ul class="summary-list">
                            <li class="summary-item">
                                <strong>MET:</strong>
                                <span style="color: #10b981;"><?php echo $totalMetCount; ?> (<?php echo $percentageMet; ?>%)</span>
                            </li>
                            <li class="summary-item">
                                <strong>NOT MET:</strong>
                                <span style="color: #ef4444;"><?php echo $totalNotMetCount; ?> (<?php echo $percentageNotMet; ?>%)</span>
                            </li>
                        </ul>
                        <div class="total-count">
                            <i class="fas fa-users"></i> Total Personnel: <?php echo $total; ?>
                        </div>
                    </div>
                </div>

                <?php if ($totalMetCount > 0 || $totalNotMetCount > 0): ?>
                <div class="charts-section">
                    <h3><i class="fas fa-chart-pie"></i> Visual Analytics</h3>
                    <div class="charts-container">
                        <div class="chart-container">
                            <div class="chart-title">Competency Status Distribution</div>
                            <canvas id="competencyChart"></canvas>
                        </div>
                        
                        <?php if ($includePL && array_sum($counts) > 0): ?>
                        <div class="chart-container">
                            <div class="chart-title">Proficiency Level Breakdown</div>
                            <canvas id="proficiencyChart"></canvas>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <div class="no-results">
                    <i class="fas fa-check-circle"></i>
                    <h3>No Gaps Found</h3>
                    <p>All personnel have met the requirements for this competency.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function removeRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            }
        }

        <?php if (isset($result) && ($totalMetCount > 0 || $totalNotMetCount > 0)): ?>
        const ctx1 = document.getElementById('competencyChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['MET', 'NOT MET (Gaps)'],
                datasets: [{
                    data: [<?php echo $totalMetCount; ?>, <?php echo $totalNotMetCount; ?>],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.5,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 13, weight: '600' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const percentage = context.dataIndex === 0 ? <?php echo $percentageMet; ?> : <?php echo $percentageNotMet; ?>;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        <?php if ($includePL && array_sum($counts) > 0): ?>
        const ctx2 = document.getElementById('proficiencyChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Basic', 'Intermediate', 'Advanced', 'Superior'],
                datasets: [{
                    data: [<?php echo $counts['Basic']; ?>, <?php echo $counts['Intermediate']; ?>, <?php echo $counts['Advance']; ?>, <?php echo $counts['Superior']; ?>],
                    backgroundColor: ['#fbbf24', '#3b82f6', '#f97316', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.5,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 13, weight: '600' } }
                    }
                }
            }
        });
        <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>

<?php $conn->close(); ?>