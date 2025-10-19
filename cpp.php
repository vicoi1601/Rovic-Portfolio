<?php
// Start session


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ppa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs and sanitize them
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);

    $fieldLabels = [
        "ppa_id" => "PPA ID",
        "item_no" => "ITEM NO",
        "plant_pos" => "PLANTILLA POSITION",
        "jg" => "JOB GRADE",
        "surname" => "SURNAME",
        "first_name" => "FIRST NAME",
        "middle_name" => "MIDDLE NAME",
        "originating_office" => "ORIGINATING RC'S",
        "designated_office" => "DESIGNATED RC'S",
        "status" => "STATUS",
        "gender" => "GENDER",
        "birth_date" => "DATE OF BIRTH",
        "designation" => "DESIGNATION",
        "date_designation" => "DATE OF DESIGNATION/REASSIGNMENT/PROMOTION/APPOINTMENT(NEWLY HIRED)/RETIREMENT",
        "self_ass" => "SELF ASSESSMENT",
        "sup_fed" => "SUPERVISOR FEEDBACK",
        "comp_con" => "COMPETENCY CONVERSATION",
        'owc' => 'Oral and Written Communication',
        'dse' => 'Delivering Service Excellence',
        'exi' => 'Exemplifying Integrity',
        'tsc' => 'Thinking Strategically and Creatively',
        'lc' => 'Leading Change',
        'collaborative' => 'Building Collaborative, Inclusive, Working',
        'performance' => 'Managing Performance and Coaching for Results',
        'nurturing' => 'Creating and Nurturing a High Performing Organization',
        'administrative' => 'Administrative Services Management',
        'assurance' => 'Assurance and Improvement of Internal Audit',
        'board' => 'Board Secretariat Management',
        'budget' => 'Budget Management',
        'business' => 'Business Development',
        'cash' => 'Cash Management',
        'change' => 'Change Adaptation',
        'compensation' => 'Compensation and Benefits',
        'conducting' => 'Conducting Audit Assignments',
        'conflict' => 'Conflict Resolution',
        'data' => 'Data Security Management',
        'domestic' => 'Domestic Port Management',
        'facilities' => 'Facilities Management',
        'financial' => 'Financial Management',
        'generating' => 'Generating Internal Audit Reports and Documentation',
        'green' => 'Green Port Technology Management',
        'sps' => 'HR Development (L&D, Succession Planning and Succession Management)',
        'hr_dev' => 'HR Development (RSP, PM and RR)',
        'ISM' => 'Information Systems Management',
        'ITS' => 'Information Technology Support',
        'IM' => 'Infrastructure Management',
        'IAIM' => 'Internal Audit Implementation and Management',
        'IAP' => 'Internal Audit Planning',
        'IPM' => 'International Port Management',
        'ITDA' => 'IT Design Architecture',
        'ITIM' => 'IT Infrastructure Management',
        'KM' => 'Knowledge Management',
        'ls_ial' => 'Legal Services (Investigation and Litigation)',
        'ls_rad' => 'Legal Services (Regulatory and Documentation)',
        'MAE' => 'Monitoring and Evaluation',
        'telecom' => 'Network, Telecommunications, Wireless and Mobility',
        'organizational' => 'Organizational and Social Sensitivity',
        'planning' => 'Planning and Organizing',
        'port_infra' => 'Port Infrastructure Development and Construction',
        'PPAD' => 'Port Planning and Design',
        'PPM' => 'Port Project Management',
        'port_service' => 'Port Service Provider Management',
        'PSLD' => 'Port Stakeholders Learning and Development',
        'PME' => 'Privatization Monitoring and Evaluation',
        'PSM' => 'Problem Solving and Making Decisions',
        'PM' => 'Process Management',
        'PROCUREMENT' => 'Procurement Management',
        'PUBLIC' => 'Public Relations',
        'RECORDS' => 'Records and Documents Management',
        'REPAIR' => 'Repair and Maintenance of Port Facilities',
        'REVENUE' => 'Revenue Generation',
        'SUPPLY' => 'Supply and Property Management',
        'SURVEY' => 'Survey Administration',
        'tech_writing' => 'Technical Writing',
        'usetech' => 'Use of Technology'
    ];

    // Construct SQL query
    $sql = "SELECT * FROM succession 
            WHERE first_name LIKE '%$first_name%' AND surname LIKE '%$surname%'";

    $plant_pos = isset($_POST['plant_pos']) ? mysqli_real_escape_string($conn, $_POST['plant_pos']) : '';
    $rc = isset($_POST['RC']) ? mysqli_real_escape_string($conn, $_POST['RC']) : '';

    $sql1 = "SELECT `plant_pos`, `RC`, `jg`, `owc`, `dse`, `exi`, `tsc`, `lc`, `collaborative`, `performance`, 
             `nurturing`, `administrative`, `assurance`, `board`, `budget`, `business`, `cash`, 
             `change`, `compensation`, `conducting`, `conflict`, `data`, `domestic`, `facilities`, 
             `financial`, `generating`, `green`, `sps`, `hr_dev`, `ISM`, `ITS`, `IM`, `IAIM`, 
             `IAP`, `IPM`, `ITDA`, `ITIM`, `KM`, `ls_ial`, `ls_rad`, `MAE`, `telecom`, 
             `organizational`, `planning`, `port_infra`, `PPAD`, `PPM`, `port_service`, 
             `PSLD`, `PME`, `PSM`, `PM`, `PROCUREMENT`, `PUBLIC`, `RECORDS`, `REPAIR`, 
             `REVENUE`, `SUPPLY`, `SURVEY`, `tech_writing`, `usetech` 
             FROM cpp 
             WHERE plant_pos LIKE '%$plant_pos%' AND RC LIKE '%$rc%'";

    $sql2 = "SELECT `plant_pos`, `designation`,`item_no`, `ppa_id`, CONCAT(surname, ', ', first_name, ' ', middle_name) AS full_name, `jg`, 
             `originating_office`, `designated_office`, `status`, `gender`,`self_ass`,`sup_fed`, `comp_con`
             FROM comp2 
             WHERE CONCAT(first_name, ' ', surname, ' ', middle_name) LIKE '%$first_name% %$surname%'";

    // Execute SQL queries
    $result = $conn->query($sql);
    $result1 = $conn->query($sql1);
    $result2 = $conn->query($sql2);

    // Check if the first query was successful
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Progression Program</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --background-color: #f8fafc;
            --card-background: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #dadff4ff 0%, #f1f1f1ff 100%);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .main-container {
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: var(--card-background);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
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

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M30 0c16.569 0 30 13.431 30 30 0 16.569-13.431 30-30 30C13.431 60 0 46.569 0 30 0 13.431 13.431 0 30 0zm0 6c13.255 0 24 10.745 24 24 0 13.255-10.745 24-24 24S6 43.255 6 30C6 16.745 16.745 6 30 6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .form-section {
            padding: 2rem;
            background: var(--background-color);
            border-bottom: 1px solid var(--border-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select {
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .results-section {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--primary-color);
            display: inline-block;
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: var(--card-background);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .info-card h3 {
            color: var(--primary-color);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card h3::before {
            content: '👤';
            font-size: 1.2rem;
        }

        .info-item {
            margin-bottom: 0.75rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: var(--text-primary);
            font-size: 1rem;
            margin-top: 0.25rem;
        }

        .competency-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            background: var(--card-background);
        }

        .competency-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .competency-table th,
        .competency-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .competency-table th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .competency-table tbody tr {
            transition: background-color 0.3s ease;
        }

        .competency-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .competency-table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }

        .status-met {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-not-met {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-proficient {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
            font-size: 0.875rem;
            display: inline-block;
        }

        .match-section {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
            border: 1px solid #bae6fd;
        }

        .match-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .match-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .match-stat {
            text-align: center;
        }

        .match-stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }

        .match-stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .progress-container {
            margin-top: 1rem;
        }

        .progress-bar-container {
            width: 100%;
            background: #e2e8f0;
            border-radius: 50px;
            height: 50px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            transition: width 0.8s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .requirements-table {
            margin-top: 2rem;
            background: var(--card-background);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .requirements-table h3 {
            background: linear-gradient(135deg, var(--secondary-color), #475569);
            color: white;
            padding: 1.5rem;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .requirements-content {
            padding: 1.5rem;
        }

        .requirements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .requirement-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease;
        }

        .requirement-item:hover {
            transform: translateX(4px);
        }

        .requirement-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .requirement-level {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .no-results-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .result-container {
            margin-bottom: 2rem;
        }

        .result-container1 {
            margin-bottom: 2rem;
        }

        .result-container3 {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .result-left {
            flex: 1;
            min-width: 200px;
        }

        .result-right {
            flex: 2;
            min-width: 300px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0.5rem;
                border-radius: 16px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .form-section,
            .results-section {
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .match-header {
                flex-direction: column;
                align-items: stretch;
            }

            .match-stats {
                justify-content: center;
            }

            .competency-table {
                font-size: 0.875rem;
            }

            .competency-table th,
            .competency-table td {
                padding: 0.75rem 0.5rem;
            }

            .result-container3 {
                flex-direction: column;
            }
        }

        @media print {
            body {
                background: white;
            }

            .form-section {
                display: none !important;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
            }

            .header {
                background: var(--primary-color) !important;
                -webkit-print-color-adjust: exact;
            }

            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="header">
                <h1>Career Progression Program</h1>
            </div>

            <div class="form-section">
                <form method="post" action="combine.php?page=career">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>"
                                   placeholder="Enter first name">
                        </div>
                        
                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" id="surname" name="surname" required 
                                   value="<?php echo isset($_POST['surname']) ? $_POST['surname'] : ''; ?>"
                                   placeholder="Enter surname">
                        </div>
                        
                        <div class="form-group">
                            <label for="plant_pos">Plantilla Position</label>
                            <select id="plant_pos" name="plant_pos">
                                <option value="" disabled <?php echo !isset($_POST['plant_pos']) ? 'selected' : ''; ?>>Select a position</option>
                                <option value="RECORDS OFFICER III" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'RECORDS OFFICER III') ? 'selected' : ''; ?>>RECORDS OFFICER III</option>
                                <option value="HRMO III" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'HRMO III') ? 'selected' : ''; ?>>HRMO III</option>
                                <option value="SR. CORPORATE ACCOUNTANT" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. CORPORATE ACCOUNTANT') ? 'selected' : ''; ?>>SR. CORPORATE ACCOUNTANT</option>
                                <option value="SR. CORPORATE BUDGET SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. CORPORATE BUDGET SPECIALIST') ? 'selected' : ''; ?>>SR. CORPORATE BUDGET SPECIALIST</option>
                                <option value="SR. STATISTICIAN" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. STATISTICIAN') ? 'selected' : ''; ?>>SR. STATISTICIAN</option>
                                <option value="SUPERVISING RESEARCHER-ANALYST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SUPERVISING RESEARCHER-ANALYST') ? 'selected' : ''; ?>>SUPERVISING RESEARCHER-ANALYST</option>
                                <option value="BUSINESS DEVELOPMENT SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'BUSINESS DEVELOPMENT SPECIALIST') ? 'selected' : ''; ?>>BUSINESS DEVELOPMENT SPECIALIST</option>
                                <option value="DATA ANALYST II" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DATA ANALYST II') ? 'selected' : ''; ?>>DATA ANALYST II</option>
                                <option value="MARKETING SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'MARKETING SPECIALIST') ? 'selected' : ''; ?>>MARKETING SPECIALIST</option>
                                <option value="SR. ECONOMIST A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. ECONOMIST A') ? 'selected' : ''; ?>>SR. ECONOMIST A</option>
                                <option value="SR. PORT TARIFF SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. PORT TARIFF SPECIALIST') ? 'selected' : ''; ?>>SR. PORT TARIFF SPECIALIST</option>
                                <option value="MEDICAL TECH. III" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'MEDICAL TECH. III') ? 'selected' : ''; ?>>MEDICAL TECH. III</option>
                                <option value="INTERNAL AUDITOR III" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'INTERNAL AUDITOR III') ? 'selected' : ''; ?>>INTERNAL AUDITOR III</option>
                                <option value="MGT. INFORMATION/ SYSTEMS DESIGN SPECIALIST B" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'MGT. INFORMATION/ SYSTEMS DESIGN SPECIALIST B') ? 'selected' : ''; ?>>MGT. INFORMATION/ SYSTEMS DESIGN SPECIALIST B</option>
                                <option value="CIVIL SECURITY OFFICER A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'CIVIL SECURITY OFFICER A') ? 'selected' : ''; ?>>CIVIL SECURITY OFFICER A</option>
                                <option value="SUPERVISING ENGINEER A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SUPERVISING ENGINEER A') ? 'selected' : ''; ?>>SUPERVISING ENGINEER A</option>
                                <option value="ENVIRONMENTAL SPECIALIST A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'ENVIRONMENTAL SPECIALIST A') ? 'selected' : ''; ?>>ENVIRONMENTAL SPECIALIST A</option>
                                <option value="BUSINESS DEVELOPMENT/ MARKETING SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'BUSINESS DEVELOPMENT/ MARKETING SPECIALIST') ? 'selected' : ''; ?>>BUSINESS DEVELOPMENT/ MARKETING SPECIALIST</option>
                                <option value="SR. HARBOR OPERATIONS OFFICER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. HARBOR OPERATIONS OFFICER') ? 'selected' : ''; ?>>SR. HARBOR OPERATIONS OFFICER</option>
                                <option value="PORT OPERATIONS SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'PORT OPERATIONS SPECIALIST') ? 'selected' : ''; ?>>PORT OPERATIONS SPECIALIST</option>
                                <option value="SR. CASHIER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. CASHIER') ? 'selected' : ''; ?>>SR. CASHIER</option>
                                <option value="ENVIRONMENT SPECIALIST A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'ENVIRONMENT SPECIALIST A') ? 'selected' : ''; ?>>ENVIRONMENT SPECIALIST A</option>
                                <option value="TRAINING SPECIALIST III" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'TRAINING SPECIALIST III') ? 'selected' : ''; ?>>TRAINING SPECIALIST III</option>
                                <option value="FINANCIAL PLANNING SPECIALIST B" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'FINANCIAL PLANNING SPECIALIST B') ? 'selected' : ''; ?>>FINANCIAL PLANNING SPECIALIST B</option>
                                <option value="SENIOR ECONOMIST A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SENIOR ECONOMIST A') ? 'selected' : ''; ?>>SENIOR ECONOMIST A</option>
                                <option value="SUPERVISING INSURANCE/ RISK OFFICER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SUPERVISING INSURANCE/ RISK OFFICER') ? 'selected' : ''; ?>>SUPERVISING INSURANCE/ RISK OFFICER</option>
                                <option value="TREASURY MANAGEMENT SPECIALIST A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'TREASURY MANAGEMENT SPECIALIST A') ? 'selected' : ''; ?>>TREASURY MANAGEMENT SPECIALIST A</option>
                                <option value="GENERAL SERVICES CHIEF B" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'GENERAL SERVICES CHIEF B') ? 'selected' : ''; ?>>GENERAL SERVICES CHIEF B</option>
                                <option value="SUPERVISING FISCAL EXAMINER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SUPERVISING FISCAL EXAMINER') ? 'selected' : ''; ?>>SUPERVISING FISCAL EXAMINER</option>
                                <option value="SR. CORP PLANNING SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. CORP PLANNING SPECIALIST') ? 'selected' : ''; ?>>SR. CORP PLANNING SPECIALIST</option>
                                <option value="SR. ECONOMIC DEVT SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SR. ECONOMIC DEVT SPECIALIST') ? 'selected' : ''; ?>>SR. ECONOMIC DEVT SPECIALIST</option>
                                <option value="SUPERVISING CASHIER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SUPERVISING CASHIER') ? 'selected' : ''; ?>>SUPERVISING CASHIER</option>
                                <option value="RECORDS MANAGEMENT CHIEF" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'RECORDS MANAGEMENT CHIEF') ? 'selected' : ''; ?>>RECORDS MANAGEMENT CHIEF</option>
                                <option value="INFORMATION OFFICER IV" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'INFORMATION OFFICER IV') ? 'selected' : ''; ?>>INFORMATION OFFICER IV</option>
                                <option value="SUPERVISING FINANCIAL SPECIALIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'SUPERVISING FINANCIAL SPECIALIST') ? 'selected' : ''; ?>>SUPERVISING FINANCIAL SPECIALIST</option>
                                <option value="PRINCIPAL ENGINEER C SURVEY" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'PRINCIPAL ENGINEER C SURVEY') ? 'selected' : ''; ?>>PRINCIPAL ENGINEER C SURVEY</option>
                                <option value="PRINCIPAL ENGINEER C PROGRAMMING" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'PRINCIPAL ENGINEER C PROGRAMMING') ? 'selected' : ''; ?>>PRINCIPAL ENGINEER C PROGRAMMING</option>
                                <option value="PRINCIPAL ENGINEER C" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'PRINCIPAL ENGINEER C') ? 'selected' : ''; ?>>PRINCIPAL ENGINEER C</option>
                                <option value="PRINCIPAL ENGINEER A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'PRINCIPAL ENGINEER A') ? 'selected' : ''; ?>>PRINCIPAL ENGINEER A</option>
                                <option value="MEDICAL OFFICER IV" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'MEDICAL OFFICER IV') ? 'selected' : ''; ?>>MEDICAL OFFICER IV</option>
                                <option value="HRMO IV" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'HRMO IV') ? 'selected' : ''; ?>>HRMO IV</option>
                                <option value="CORPORATE FINANCE SERVICES CHIEF" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'CORPORATE FINANCE SERVICES CHIEF') ? 'selected' : ''; ?>>CORPORATE FINANCE SERVICES CHIEF</option>
                                <option value="CHIEF SAFETY OFFICER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'CHIEF SAFETY OFFICER') ? 'selected' : ''; ?>>CHIEF SAFETY OFFICER</option>
                                <option value="CHIEF ECONOMIST" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'CHIEF ECONOMIST') ? 'selected' : ''; ?>>CHIEF ECONOMIST</option>
                                <option value="PORT TARIFF CHIEF" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'PORT TARIFF CHIEF') ? 'selected' : ''; ?>>PORT TARIFF CHIEF</option>
                                <option value="DIVISION MANAGER A" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER A') ? 'selected' : ''; ?>>DIVISION MANAGER A</option>
                                <option value="DIVISION MANAGER B" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER B') ? 'selected' : ''; ?>>DIVISION MANAGER B</option>
                                <option value="DIVISION MANAGER C" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER C') ? 'selected' : ''; ?>>DIVISION MANAGER C</option>
                                <option value="DIVISION MANAGER D" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER D') ? 'selected' : ''; ?>>DIVISION MANAGER D</option>
                                <option value="DIVISION MANAGER A(ORSD)" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER A(ORSD)') ? 'selected' : ''; ?>>DIVISION MANAGER A(ORSD)</option>
                                <option value="DIVISION MANAGER A(ISPQSD)" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER A(ISPQSD)') ? 'selected' : ''; ?>>DIVISION MANAGER A(ISPQSD)</option>
                                <option value="DIVISION MANAGER A(ADSD)" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'DIVISION MANAGER A(ADSD)') ? 'selected' : ''; ?>>DIVISION MANAGER A(ADSD)</option>
                                <option value="HARBOR MASTER" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'HARBOR MASTER') ? 'selected' : ''; ?>>HARBOR MASTER</option>
                                <option value="ATTORNEY V" <?php echo (isset($_POST['plant_pos']) && $_POST['plant_pos'] == 'ATTORNEY V') ? 'selected' : ''; ?>>ATTORNEY V</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="RC">Regional Center</label>
                            <select id="RC" name="RC" required>
                                <option value="" disabled <?php echo !isset($_POST['RC']) ? 'selected' : ''; ?>>Select Regional Center</option>
                                <option value="ASD - GSD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'ASD - GSD') ? 'selected' : ''; ?>>ASD - GSD</option>
                                <option value="ASD - RCD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'ASD - RCD') ? 'selected' : ''; ?>>ASD - RCD</option>
                                <option value="CDD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'CDD') ? 'selected' : ''; ?>>CDD</option>
                                <option value="CONTROLLERSHIP - ACC." <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'CONTROLLERSHIP - ACC.') ? 'selected' : ''; ?>>CONTROLLERSHIP - ACC.</option>
                                <option value="CONTROLLERSHIP - FC" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'CONTROLLERSHIP - FC') ? 'selected' : ''; ?>>CONTROLLERSHIP - FC</option>
                                <option value="CPD - MED" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'CPD - MED') ? 'selected' : ''; ?>>CPD - MED</option>
                                <option value="CSD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'CSD') ? 'selected' : ''; ?>>CSD</option>
                                <option value="HRSD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'HRSD') ? 'selected' : ''; ?>>HRSD</option>
                                <option value="IAD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'IAD') ? 'selected' : ''; ?>>IAD</option>
                                <option value="ICTD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'ICTD') ? 'selected' : ''; ?>>ICTD</option>
                                <option value="ISAS" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'ISAS') ? 'selected' : ''; ?>>ISAS</option>
                                <option value="PCMD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PCMD') ? 'selected' : ''; ?>>PCMD</option>
                                <option value="PMO NORTH TSD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO NORTH TSD') ? 'selected' : ''; ?>>PMO NORTH TSD</option>
                                <option value="PMO NORTH VTS" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO NORTH VTS') ? 'selected' : ''; ?>>PMO NORTH VTS</option>
                                <option value="PMO SOUTH REMD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO SOUTH REMD') ? 'selected' : ''; ?>>PMO SOUTH REMD</option>
                                <option value="PMO SOUTH RMD - FINANCE" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO SOUTH RMD - FINANCE') ? 'selected' : ''; ?>>PMO SOUTH RMD - FINANCE</option>
                                <option value="POSD - MD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'POSD - MD') ? 'selected' : ''; ?>>POSD - MD</option>
                                <option value="POSD - TSD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'POSD - TSD') ? 'selected' : ''; ?>>POSD - TSD</option>
                                <option value="POSD - SEMD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'POSD - SEMD') ? 'selected' : ''; ?>>POSD - SEMD</option>
                                <option value="PPATI" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PPATI') ? 'selected' : ''; ?>>PPATI</option>
                                <option value="PPD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PPD') ? 'selected' : ''; ?>>PPD</option>
                                <option value="PPDD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PPDD') ? 'selected' : ''; ?>>PPDD</option>
                                <option value="TREASURY - CM" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'TREASURY - CM') ? 'selected' : ''; ?>>TREASURY - CM</option>
                                <option value="TREASURY - MS" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'TREASURY - MS') ? 'selected' : ''; ?>>TREASURY - MS</option>
                                <option value="LSD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'LSD') ? 'selected' : ''; ?>>LSD</option>
                                <option value="OCBS" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'OCBS') ? 'selected' : ''; ?>>OCBS</option>
                                <option value="PMO NCR NORTH SOUTH - RMD" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO NCR NORTH SOUTH - RMD') ? 'selected' : ''; ?>>PMO NCR NORTH SOUTH - RMD</option>
                                <option value="PMO NCR NORTH SOUTH - OPM" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO NCR NORTH SOUTH - OPM') ? 'selected' : ''; ?>>PMO NCR NORTH SOUTH - OPM</option>
                                <option value="PMO NCR NORTH - MICT" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO NCR NORTH - MICT') ? 'selected' : ''; ?>>PMO NCR NORTH - MICT</option>
                                <option value="TMO" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'TMO') ? 'selected' : ''; ?>>TMO</option>
                                <option value="PMO - OFFICE OF THE PORT MANAGER" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO - OFFICE OF THE PORT MANAGER') ? 'selected' : ''; ?>>PMO - OFFICE OF THE PORT MANAGER</option>
                                <option value="PMO - FINANCE DIVISION" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO - FINANCE DIVISION') ? 'selected' : ''; ?>>PMO - FINANCE DIVISION</option>
                                <option value="PMO - ADMINISTRATIVE DIVISION" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO - ADMINISTRATIVE DIVISION') ? 'selected' : ''; ?>>PMO - ADMINISTRATIVE DIVISION</option>
                                <option value="PMO - ENGINEERING DIVISION" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO - ENGINEERING DIVISION') ? 'selected' : ''; ?>>PMO - ENGINEERING DIVISION</option>
                                <option value="PMO - PORT SERVICE DIVISION" <?php echo (isset($_POST['RC']) && $_POST['RC'] == 'PMO - PORT SERVICE DIVISION') ? 'selected' : ''; ?>>PMO - PORT SERVICE DIVISION</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            🔍 Search Personnel
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="printResults()">
                            🖨️ Print Report
                        </button>
                    </div>
                </form>
            </div>

            <div class="results-section">
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                
                <!-- Personnel Information -->
                <?php if (isset($result2) && $result2->num_rows > 0): ?>
                    <h2 class="section-title">Personnel Information</h2>
                    <div class="info-cards">
                        <?php while ($row = $result2->fetch_assoc()): ?>
                        <div class="info-card">
                            <h3>Personnel Details</h3>
                            <?php foreach ($row as $key => $value): ?>
                                <?php if (!empty($value)): ?>
                                    <?php $label = array_key_exists($key, $fieldLabels) ? $fieldLabels[$key] : ucwords(str_replace("_", " ", $key)); ?>
                                    <div class="info-item">
                                        <div class="info-label"><?php echo $label; ?></div>
                                        <div class="info-value"><?php echo $value; ?></div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>

                <!-- Competency Assessment -->
                <?php if (isset($result) && $result->num_rows > 0): ?>
                    <h2 class="section-title">Competency Assessment</h2>
                    
                    <table class="competency-table">
                        <thead>
                            <tr>
                                <th>Competencies</th>
                                <th>Required</th>
                                <th>Proficiency Level</th>
                                <th>Advanced</th>
                                <th>Advanced Level</th>
                                <th>Level of Competence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $competency_map = [
                                'owc' => 'Oral and Written Communication',
                                'dse' => 'Delivering Service Excellence',
                                'exi' => 'Exemplifying Integrity',
                                'tsc' => 'Thinking Strategically and Creatively',
                                'lc' => 'Leading Change',
                                'collaborative' => 'Building Collaborative, Inclusive, Working',
                                'performance' => 'Managing Performance and Coaching for Results',
                                'nurturing' => 'Creating and Nurturing a High Performing Organization',
                                'administrative' => 'Administrative Services Management',
                                'assurance' => 'Assurance and Improvement of Internal Audit',
                                'board' => 'Board Secretariat Management',
                                'budget' => 'Budget Management',
                                'business' => 'Business Development',
                                'cash' => 'Cash Management',
                                'change' => 'Change Adaptation',
                                'compensation' => 'Compensation and Benefits',
                                'conducting' => 'Conducting Audit Assignments',
                                'conflict' => 'Conflict Resolution',
                                'data' => 'Data Security Management',
                                'domestic' => 'Domestic Port Management',
                                'facilities' => 'Facilities Management',
                                'financial' => 'Financial Management',
                                'generating' => 'Generating Internal Audit Reports and Documentation',
                                'green' => 'Green Port Technology Management',
                                'sps' => 'HR Development (L&D, Succession Planning and Succession Management)',
                                'hr_dev' => 'HR Development (RSP, PM and RR)',
                                'ISM' => 'Information Systems Management',
                                'ITS' => 'Information Technology Support',
                                'IM' => 'Infrastructure Management',
                                'IAIM' => 'Internal Audit Implementation and Management',
                                'IAP' => 'Internal Audit Planning',
                                'IPM' => 'International Port Management',
                                'ITDA' => 'IT Design Architecture',
                                'ITIM' => 'IT Infrastructure Management',
                                'KM' => 'Knowledge Management',
                                'ls_ial' => 'Legal Services (Investigation and Litigation)',
                                'ls_rad' => 'Legal Services (Regulatory and Documentation)',
                                'MAE' => 'Monitoring and Evaluation',
                                'telecom' => 'Network, Telecommunications, Wireless and Mobility',
                                'organizational' => 'Organizational and Social Sensitivity',
                                'planning' => 'Planning and Organizing',
                                'port_infra' => 'Port Infrastructure Development and Construction',
                                'PPAD' => 'Port Planning and Design',
                                'PPM' => 'Port Project Management',
                                'port_service' => 'Port Service Provider Management',
                                'PSLD' => 'Port Stakeholders Learning and Development',
                                'PME' => 'Privatization Monitoring and Evaluation',
                                'PSM' => 'Problem Solving and Making Decisions',
                                'PM' => 'Process Management',
                                'PROCUREMENT' => 'Procurement Management',
                                'PUBLIC' => 'Public Relations',
                                'RECORDS' => 'Records and Documents Management',
                                'REPAIR' => 'Repair and Maintenance of Port Facilities',
                                'REVENUE' => 'Revenue Generation',
                                'SUPPLY' => 'Supply and Property Management',
                                'SURVEY' => 'Survey Administration',
                                'tech_writing' => 'Technical Writing',
                                'usetech' => 'Use of Technology'
                            ];

                            $displayed_competencies = [];
                            
                            while ($row = $result->fetch_assoc()) {
                                foreach ($competency_map as $field => $competency_name) {
                                    if ($row[$field] != "MET" && $row[$field] != "NOT MET") {
                                        continue;
                                    }

                                    if (in_array($competency_name, $displayed_competencies)) {
                                        continue;
                                    }

                                    $displayed_competencies[] = $competency_name;

                                    $adv_field = "adv_" . $field;
                                    $status = ($row[$field] == 'MET') ? 'MET' : 'NOT MET';
                                    $adv_status = (isset($row[$adv_field]) && ($row[$adv_field] == 'MET' || $row[$adv_field] == 'NOT MET')) ? $row[$adv_field] : '';

                                    $required_pl = (int)$row[$field . '_pl'];
                                    $required_pl_display = $required_pl;

                                    $advanced_pl = $required_pl;
                                    if ($advanced_pl < 4) {
                                        $advanced_pl++;
                                    } else {
                                        $advanced_pl = "REQUIRED COMPETENCY IS THE HIGHEST PROFICIENCY LEVEL";
                                    }

                                    if ($status == 'MET' && $adv_status == 'MET') {
                                        if ($required_pl == '4' && $advanced_pl == 'REQUIRED COMPETENCY IS THE HIGHEST PROFICIENCY LEVEL') {
                                            $level_of_competence = 'REQUIRED';
                                        } else {
                                            $level_of_competence = 'ADVANCED';
                                        }
                                    } else {
                                        $level_of_competence = 'REQUIRED';
                                    }

                                    echo "<tr>
                                            <td>$competency_name</td>
                                            <td><span class='" . ($status == 'MET' ? 'status-met' : 'status-not-met') . "'>$status</span></td>
                                            <td><span class='status-proficient'>$required_pl_display</span></td>
                                            <td><span class='" . ($adv_status == 'MET' ? 'status-met' : 'status-not-met') . "'>$adv_status</span></td>
                                            <td><span class='status-proficient'>$advanced_pl</span></td>
                                            <td>$level_of_competence</td>
                                          </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Position Requirements -->
                <?php if (isset($result1) && $result1->num_rows > 0): ?>
                    <?php 
                    $requiredCompetencies = [];
                    $matchingCompetencies = 0;
                    ?>
                    
                    <div class="requirements-table">
                        <h3>📋 Required Competencies for <?php echo $plant_pos; ?> in <?php echo $rc; ?></h3>
                        <div class="requirements-content">
                            <div class="requirements-grid">
                                <?php while ($row = $result1->fetch_assoc()): ?>
                                    <?php foreach ($row as $key => $value): ?>
                                        <?php if (in_array($key, ['jg', 'RC', 'plant_pos'])) continue; ?>
                                        <?php if (!empty($value)): ?>
                                            <?php 
                                            $label = array_key_exists($key, $fieldLabels) ? $fieldLabels[$key] : ucwords(str_replace("_", " ", $key));
                                            $requiredCompetencies[] = $label;
                                            ?>
                                            <div class="requirement-item">
                                                <div class="requirement-name"><?php echo $label; ?></div>
                                                <div class="requirement-level">Proficiency Level: <?php echo $value; ?></div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Job Match Analysis -->
                    <?php
                    if (isset($displayed_competencies)) {
                        foreach ($displayed_competencies as $competency_name) {
                            if (in_array($competency_name, $requiredCompetencies)) {
                                $matchingCompetencies++;
                            }
                        }
                    }
                    
                    $totalRequiredCompetencies = count($requiredCompetencies);
                    if ($totalRequiredCompetencies > 0) {
                        $matchingPercentage = ($matchingCompetencies / $totalRequiredCompetencies) * 100;
                    ?>
                        <div class="match-section">
                            <div class="match-header">
                                <h2 class="section-title" style="margin-bottom: 0; border: none;">Job Match Analysis</h2>
                                <div class="match-stats">
                                    <div class="match-stat">
                                        <span class="match-stat-value"><?php echo $matchingCompetencies; ?></span>
                                        <span class="match-stat-label">Matching Competencies</span>
                                    </div>
                                    <div class="match-stat">
                                        <span class="match-stat-value"><?php echo $totalRequiredCompetencies; ?></span>
                                        <span class="match-stat-label">Total Required</span>
                                    </div>
                                    <div class="match-stat">
                                        <span class="match-stat-value"><?php echo round($matchingPercentage, 2); ?>%</span>
                                        <span class="match-stat-label">Match Percentage</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="progress-container">
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: <?php echo $matchingPercentage; ?>%;">
                                        <?php echo round($matchingPercentage, 2); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php endif; ?>

                <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <div class="no-results">
                        <div class="no-results-icon">🔍</div>
                        <h3>No Results Found</h3>
                        <p>Please check your search criteria and try again.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function printResults() {
            window.print();
        }

        // Form enhancements
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
                
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });

            // Add search feedback
            const firstNameInput = document.getElementById('first_name');
            const surnameInput = document.getElementById('surname');
            
            [firstNameInput, surnameInput].forEach(input => {
                input.addEventListener('input', function() {
                    this.style.borderColor = this.value.length > 0 ? '#10b981' : '#e2e8f0';
                });
            });

            // Add dependent dropdown behavior
            const positionSelect = document.getElementById('plant_pos');
            const rcSelect = document.getElementById('RC');
            
            positionSelect.addEventListener('change', function() {
                rcSelect.style.borderColor = '#10b981';
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    document.querySelector('form').submit();
                }
                
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    printResults();
                }
            });

            // Add hover effects to tables
            const tableRows = document.querySelectorAll('.competency-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.transition = 'transform 0.3s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>