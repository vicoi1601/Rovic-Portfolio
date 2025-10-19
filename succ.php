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
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs and sanitize them
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);

    // Construct SQL query
    $sql = "SELECT * FROM succession 
            WHERE first_name LIKE '%$first_name%' AND surname LIKE '%$surname%'";

    $sql1 = "SELECT `plant_pos`, `designation`,`item_no`, `ppa_id`,  CONCAT(surname, ', ', first_name, ' ', middle_name) AS full_name,  `jg`, 
                    `originating_office`, `designated_office`, `status`, `gender`,`self_ass`,`sup_fed`, `comp_con`
             FROM comp2 
             WHERE CONCAT(first_name, ' ', surname, ' ', middle_name) LIKE '%$first_name% %$surname%'";

    // Execute SQL query
    $result = $conn->query($sql);
    $result1 = $conn->query($sql1);

    // Check if query was successful
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
    <title>SDMS - Succession Development Monitoring System color</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #d0d5edff 0%, #f7f7f8ff 100%);
            min-height: 100vh;
            color: #2d3748;
            line-height: 1.6;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }
         h1 {
      color:  #1976d2;
    }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;<
        }

        .search-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto auto;
            gap: 1.5rem;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group input[type="text"] {
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .results-section {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .results-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
        }

        .results-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .table-container {
            overflow-x: auto;
        }

        .competency-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .competency-table th {
            background: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .competency-table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .competency-table tbody tr:hover {
            background: #f8fafc;
            transition: background-color 0.2s ease;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-met {
            background: #d1fae5;
            color: #065f46;
        }

        .status-not-met {
            background: #fee2e2;
            color: #991b1b;
        }

        .proficiency-level {
            font-weight: 600;
            color: #1e293b;
            text-align: center;
        }

        .personnel-card {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 350px;
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-left: 4px solid #667eea;
            max-height: 80vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .personnel-card.hidden {
            transform: translateX(100%);
            opacity: 0;
            pointer-events: none;
        }

        .toggle-btn {
            position: fixed;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 1001;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .toggle-btn:hover {
            background: #5a67d8;
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-btn.card-hidden {
            right: 20px;
        }

        .toggle-btn.card-visible {
            right: 390px;
        }

        .personnel-card h3 {
            color: #1e293b;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-item {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid #e2e8f0;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #1e293b;
            font-weight: 500;
        }

        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 1024px) {
            .personnel-card {
                position: static;
                width: 100%;
                margin-top: 2rem;
                max-height: none;
                transform: none !important;
                opacity: 1 !important;
                pointer-events: auto !important;
            }

            .personnel-card.hidden {
                display: none;
            }
            
            .toggle-btn {
                position: static;
                margin: 1rem auto;
                transform: none;
                right: auto;
                top: auto;
                border-radius: 12px;
                width: auto;
                height: auto;
                padding: 0.875rem 2rem;
            }

            .toggle-btn.card-hidden,
            .toggle-btn.card-visible {
                right: auto;
            }
            
            .main-container {
                padding: 1rem;
            }
        }

        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .search-card {
                padding: 1.5rem;
            }
        }

        @media print {
            body {
                background: white;
            }
            
            .search-card,
            .personnel-card {
                display: none !important;
            }
            
            .results-section {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }
            
            .main-container {
                max-width: none;
                padding: 0;
            }
            
            .competency-table td:nth-child(10),
            .competency-table th:nth-child(10) {
                display: none;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1><i class="fas fa-users-cog"></i> Succession Development Monitoring System</h1>
            
        </div>

        <div class="search-card">
            <form method="post" action="combine.php?page=succession" class="search-form">
                <div class="form-group">
                    <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                    <input type="text" id="first_name" name="first_name" 
                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" 
                           placeholder="Enter first name..." required>
                </div>
                
                <div class="form-group">
                    <label for="surname"><i class="fas fa-user-tag"></i> Surname</label>
                    <input type="text" id="surname" name="surname" 
                           value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" 
                           placeholder="Enter surname..." required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                
                <button type="button" class="btn btn-secondary" onclick="printResults()">
                    <i class="fas fa-print"></i> Print
                </button>
            </form>
        </div>

        <?php if (isset($result) && $result->num_rows > 0): ?>
            <div class="results-section fade-in">
                <div class="results-header">
                    <h2><i class="fas fa-chart-line"></i> Competency Assessment Results</h2>
                  
                </div>
                
                <div class="table-container">
                    <table class="competency-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-clipboard-list"></i> Competencies</th>
                                <th><i class="fas fa-check-circle"></i> Required</th>
                                <th><i class="fas fa-signal"></i> Proficiency Level</th>
                                <th><i class="fas fa-star"></i> Advanced</th>
                                <th><i class="fas fa-signal"></i> Proficiency Level</th>
                                <th><i class="fas fa-trophy"></i> Level of Competence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Full competency names 
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
                            
                            // Initialize a variable to keep track of competencies that have already been displayed
                            $displayed_competencies = [];
                            
                            // Fetch and display rows
                            while ($row = $result->fetch_assoc()) {
                                foreach ($competency_map as $field => $competency_name) {
                                    // Skip rows without "MET" or "NOT MET" value
                                    if ($row[$field] != "MET" && $row[$field] != "NOT MET") {
                                        continue;
                                    }

                                    // Only display the competency once
                                    if (in_array($competency_name, $displayed_competencies)) {
                                        continue;
                                    }

                                    // Add the competency to the displayed list
                                    $displayed_competencies[] = $competency_name;

                                    // Check if the advanced competency exists
                                    $adv_field = "adv_" . $field;

                                    // Determine competency status (MET/NOT MET)
                                    $status = ($row[$field] == 'MET') ? 'MET' : 'NOT MET';
                                    $adv_status = (isset($row[$adv_field]) && ($row[$adv_field] == 'MET' || $row[$adv_field] == 'NOT MET')) ? $row[$adv_field] : '';

                                    // Get Proficiency Level (For the Required Competency)
                                    $required_pl = (int)$row[$field . '_pl'];  // Assuming proficiency is stored as an integer
                                    $required_pl_display = $required_pl;

                                    // Calculate the Advanced Proficiency Level based on the Required Proficiency Level
                                    $advanced_pl = $required_pl;  // Initially, advanced proficiency level is the same as required

                                    // If proficiency level is less than 4, increment for advanced
                                    if ($advanced_pl < 4) {
                                        $advanced_pl++;
                                    } else {
                                        $advanced_pl = "REQUIRED COMPETENCY IS THE HIGHEST PROFICIENCY LEVEL";
                                    }
                                    
                                    //Determine Level of Competence based on Required and Advanced Status
                                    if ($status == 'MET' && $adv_status == 'MET') {
                                        if ($required_pl == '4' && $advanced_pl == 'REQUIRED COMPETENCY IS THE HIGHEST PROFICIENCY LEVEL') {
                                            $level_of_competence = 'REQUIRED';
                                        } else {
                                            $level_of_competence = 'ADVANCED';
                                        }
                                    } else {
                                        $level_of_competence = 'REQUIRED';
                                    }
                                    
                                    // Display the row with the competency, proficiency, and advanced competency status
                                    echo "<tr>
                                            <td><strong>$competency_name</strong></td>
                                            <td><span class='status-badge " . ($status == 'MET' ? 'status-met' : 'status-not-met') . "'>$status</span></td>
                                            <td class='proficiency-level'>$required_pl_display</td>
                                            <td><span class='status-badge " . ($adv_status == 'MET' ? 'status-met' : 'status-not-met') . "'>$adv_status</span></td>
                                            <td class='proficiency-level'>$advanced_pl</td>
                                            <td><strong>$level_of_competence</strong></td>
                                          </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php
            // Display personnel information outside the competency loop
            if (isset($result1) && $result1->num_rows > 0): ?>
                <button class="toggle-btn card-visible" id="toggleBtn" onclick="togglePersonnelCard()" title="Hide Personnel Info">
                    <i class="fas fa-chevron-right" id="toggleIcon"></i>
                </button>
                <div class="personnel-card fade-in" id="personnelCard">
                    <h3><i class="fas fa-id-card"></i> Personnel Information</h3>
                    <?php while ($personnel_row = $result1->fetch_assoc()): ?>
                        <?php foreach ($personnel_row as $key => $value): ?>
                            <?php if (!empty($value)): ?>
                                <?php $label = array_key_exists($key, $fieldLabels) ? $fieldLabels[$key] : ucwords(str_replace("_", " ", $key)); ?>
                                <div class="info-item">
                                    <div class="info-label"><?php echo htmlspecialchars($label); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($value); ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="results-section fade-in">
                <div class="no-results">
                    <i class="fas fa-search-minus"></i>
                    <h3>No Results Found</h3>
                    <p>No competency data found for the specified search criteria. Please try different search terms.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function printResults() {
            window.print();
        }

        function togglePersonnelCard() {
            const card = document.getElementById('personnelCard');
            const button = document.getElementById('toggleBtn');
            const icon = document.getElementById('toggleIcon');
            
            if (card && button && icon) {
                card.classList.toggle('hidden');
                
                if (card.classList.contains('hidden')) {
                    // Card is now hidden
                    button.classList.remove('card-visible');
                    button.classList.add('card-hidden');
                    button.title = 'Show Personnel Info';
                    icon.className = 'fas fa-chevron-left';
                } else {
                    // Card is now visible
                    button.classList.remove('card-hidden');
                    button.classList.add('card-visible');
                    button.title = 'Hide Personnel Info';
                    icon.className = 'fas fa-chevron-right';
                }
            }
        }

        // Auto-hide card on smaller screens when clicking outside
        document.addEventListener('click', function(event) {
            const card = document.getElementById('personnelCard');
            const button = document.getElementById('toggleBtn');
            
            if (window.innerWidth <= 1024 && card && button) {
                if (!card.contains(event.target) && !button.contains(event.target)) {
                    if (!card.classList.contains('hidden')) {
                        togglePersonnelCard();
                    }
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const card = document.getElementById('personnelCard');
            const button = document.getElementById('toggleBtn');
            
            if (window.innerWidth <= 1024) {
                // Reset classes for mobile view
                if (card) card.classList.remove('hidden');
                if (button) {
                    button.classList.remove('card-visible', 'card-hidden');
                }
            }
        });
    </script>
</body>
</html>