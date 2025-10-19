<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ppa");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// === COMPETENCY GAP SUMMARY ===
$gapQuery = "SELECT 
                COUNT(*) AS total_competency_gaps,
                SUM(CASE WHEN met_notmet = 'MET' THEN 1 ELSE 0 END) AS bridged_gaps,
                SUM(CASE WHEN met_notmet = 'NOT MET' THEN 1 ELSE 0 END) AS remaining_gaps
             FROM pmf";
$gapResult = $conn->query($gapQuery)->fetch_assoc();

// === TRAINING EFFECTIVENESS PER TYPE ===
$trainQuery = "SELECT 
                  type,
                  SUM(CASE WHEN met_notmet = 'MET' THEN 1 ELSE 0 END) AS met_count,
                  SUM(CASE WHEN met_notmet = 'NOT MET' THEN 1 ELSE 0 END) AS not_met_count
               FROM pmf
               GROUP BY type";
$trainResult = $conn->query($trainQuery);

$trainingLabels = [];
$metData = [];
$notMetData = [];

while ($row = $trainResult->fetch_assoc()) {
    $trainingLabels[] = $row['type'];
    $metData[] = $row['met_count'];
    $notMetData[] = $row['not_met_count'];
}

// === COMPETENCY GAP PER COMP_GAP ===
$compQuery = "SELECT 
                 comp_gap,
                 COUNT(*) AS total_interventions,
                 SUM(CASE WHEN met_notmet = 'MET' THEN 1 ELSE 0 END) AS met_count,
                 SUM(CASE WHEN met_notmet = 'NOT MET' THEN 1 ELSE 0 END) AS not_met_count
              FROM pmf
              GROUP BY comp_gap
              ORDER BY comp_gap";
$compResult = $conn->query($compQuery);

$compLabels = [];
$compMet = [];
$compNotMet = [];
$compTable = "";

while ($row = $compResult->fetch_assoc()) {
    $compLabels[] = $row['comp_gap'];
    $compMet[] = $row['met_count'];
    $compNotMet[] = $row['not_met_count'];
    $successRate = $row['total_interventions'] > 0 ? round(($row['met_count'] / $row['total_interventions']) * 100, 1) : 0;
    $compTable .= "<tr>
                      <td><div class='gap-name'>{$row['comp_gap']}</div></td>
                      <td><span class='total-badge'>{$row['total_interventions']}</span></td>
                      <td><span class='met-badge'>{$row['met_count']}</span></td>
                      <td><span class='not-met-badge'>{$row['not_met_count']}</span></td>
                      <td>
                          <div class='progress-container'>
                              <div class='progress-bar' style='width: {$successRate}%'></div>
                              <span class='progress-text'>{$successRate}%</span>
                          </div>
                      </td>
                   </tr>";
}

$overallSuccessRate = $gapResult['total_competency_gaps'] > 0 ? 
    round(($gapResult['bridged_gaps'] / $gapResult['total_competency_gaps']) * 100, 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competency Gap Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            text-align: center;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1976d2, #1565c0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .dashboard-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            font-weight: 400;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .stat-icon.success { color: #10b981; }
        .stat-icon.danger { color: #ef4444; }
        .stat-icon.info { color: #3b82f6; }
        .stat-icon.warning { color: #f59e0b; }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .success-rate {
            margin-top: 1rem;
            padding: 1rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 12px;
            font-weight: 600;
        }

        .chart-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-icon {
            color: #1976d2;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            align-items: center;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .small-chart {
            height: 300px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background: linear-gradient(135deg, #20354dff, #1976d2);
            color: white;
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f8fafc;
        }

        .gap-name {
            font-weight: 600;
            color: #374151;
        }

        .total-badge {
            background: #e5e7eb;
            color: #374151;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .met-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .not-met-badge {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .progress-container {
            position: relative;
            background: #f3f4f6;
            border-radius: 50px;
            height: 24px;
            min-width: 100px;
        }

        .progress-bar {
            background: linear-gradient(135deg, #10b981, #059669);
            height: 100%;
            border-radius: 50px;
            transition: width 0.3s ease;
            min-width: 20px;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
        }

        .footer {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2rem;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 0 1rem;
            }
            
            .chart-section {
                padding: 1.5rem;
            }
            
            th, td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }
        }

        /* Animation for loading */
        .chart-section {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                Competency Gap Analytics
            </h1>
            <p class="dashboard-subtitle">Performance Management Framework Dashboard</p>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-check-circle stat-icon success"></i>
                <div class="stat-number" style="color: #10b981;"><?= $gapResult['bridged_gaps'] ?></div>
                <div class="stat-label">Bridged Gaps</div>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-exclamation-circle stat-icon danger"></i>
                <div class="stat-number" style="color: #ef4444;"><?= $gapResult['remaining_gaps'] ?></div>
                <div class="stat-label">Remaining Gaps</div>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-chart-bar stat-icon info"></i>
                <div class="stat-number" style="color: #3b82f6;"><?= $gapResult['total_competency_gaps'] ?></div>
                <div class="stat-label">Total Gaps</div>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-trophy stat-icon warning"></i>
                <div class="stat-number" style="color: #f59e0b;"><?= $overallSuccessRate ?>%</div>
                <div class="stat-label">Success Rate</div>
                <div class="success-rate">
                    Overall Performance Indicator
                </div>
            </div>
        </div>

        <!-- Overview Charts -->
        <div class="chart-section">
            <h2 class="section-title">
                <i class="fas fa-pie-chart section-icon"></i>
                Gap Distribution Overview
            </h2>
            <div class="charts-grid">
                <div class="small-chart">
                    <canvas id="gapChart"></canvas>
                </div>
                <div>
                    <h3 style="color: #6b7280; margin-bottom: 1rem;">Key Insights</h3>
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px;">
                        <p style="margin-bottom: 1rem;"><strong>Bridging Rate:</strong> <?= $overallSuccessRate ?>% of competency gaps have been successfully addressed.</p>
                        <p style="margin-bottom: 1rem;"><strong>Active Gaps:</strong> <?= $gapResult['remaining_gaps'] ?> gaps still require intervention.</p>
                        <p><strong>Total Coverage:</strong> <?= $gapResult['total_competency_gaps'] ?> competency areas assessed.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Effectiveness -->
        <div class="chart-section">
            <h2 class="section-title">
                <i class="fas fa-graduation-cap section-icon"></i>
                Training Effectiveness by Type
            </h2>
            <div class="chart-container">
                <canvas id="trainingChart"></canvas>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="chart-section">
            <h2 class="section-title">
                <i class="fas fa-table section-icon"></i>
                Detailed Competency Gap Analysis
            </h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Competency Gap</th>
                            <th>Total Interventions</th>
                            <th>MET</th>
                            <th>NOT MET</th>
                            <th>Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $compTable ?>
                    </tbody>
                </table>
            </div>
        </div>

      
    <script>
        // Enhanced Chart.js defaults
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#374151';

        // GAP CHART (Donut with enhanced styling)
        new Chart(document.getElementById('gapChart'), {
            type: 'doughnut',
            data: {
                labels: ['Bridged (MET)', 'Remaining (NOT MET)'],
                datasets: [{
                    data: [<?= $gapResult['bridged_gaps'] ?>, <?= $gapResult['remaining_gaps'] ?>],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 14, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                cutout: '60%'
            }
        });

        // TRAINING EFFECTIVENESS (Enhanced Stacked Bar)
        new Chart(document.getElementById('trainingChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($trainingLabels) ?>,
                datasets: [
                    {
                        label: 'MET (Bridged)',
                        data: <?= json_encode($metData) ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                        borderSkipped: false
                    },
                    {
                        label: 'NOT MET (Remaining)',
                        data: <?= json_encode($notMetData) ?>,
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: { size: 14, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: { font: { size: 12, weight: '500' } }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: { font: { size: 12, weight: '500' } }
                    }
                }
            }
        });

     
    </script>
</body>
</html>