<?php
// Start session


// Check if the user is logged in; if not, redirect to login page

// Assuming you have a database connection, replace these credentials with your own
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

// Define custom labels for each field
$fieldLabels = [
        "ppa_id" => "PPA ID",
        "item_no" => "Item No",
        "plant_pos" => "Plantilla Position",
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
        "owc" => "ORAL AND WRITTEN COMMUNICATION",
        "adv_owc" => "ORAL AND WRITTEN COMMUNICATION",
        "dse" => "DELIVERING SERVICE EXCELLENCE",
        "adv_dse" => "DELIVERING SERVICE EXCELLENCE",
        "exi" => "EXEMPLIFYING INTEGRITY",
        "adv_exi" => "EXEMPLIFYING INTEGRITY",
        "tsc" => "THINKING STRATEGICALLY AND CREATIVELY",
        "adv_tsc" => "THINKING STRATEGICALLY AND CREATIVELY",
        "lc" => "LEADING CHANGE",
        "adv_lc" => "LEADING CHANGE",
        "collaborative" => "COLLABORATIVE, INCLUSIVE, WORKING",
        "adv_collaborative" => "COLLABORATIVE, INCLUSIVE, WORKING",
        "performance" => "PERFORMANCE AND COACHING FOR RESULTS",
        "adv_performance" => "PERFORMANCE AND COACHING FOR RESULTS",
        "nurturing" => "NURTURING A HIGH PERFORMING ORGANIZATION",
        "adv_nurturing" => "NURTURING A HIGH PERFORMING ORGANIZATION",
        "administrative" => "ADMINISTRATIVE SERVICES MANAGEMENT",
        "adv_administrative" => "ADMINISTRATIVE SERVICES MANAGEMENT",
        "assurance" => "ASSURANCE AND IMPROVEMENT OF INTERNAL AUDIT",
        "adv_assurance" => "ASSURANCE AND IMPROVEMENT OF INTERNAL AUDIT",
        "board" => "BOARD SECRETARIAT MANAGEMENT",
        "budget" => "BUDGET MANAGEMENT",
        "business" => "BUSINESS DEVELOPMENT",
        "cash" => "CASH MANAGEMENT",
        "change" => "CHANGE ADAPTATION",
        "compensation" => "COMPENSATION AND BENEFITS",
        "conducting" => "CONDUCTING AUDIT ASSIGNMENTS",
        "conflict" => "CONFLICT RESOLUTION",
        "data" => "DATA SECURITY MANAGEMENT",
        "domestic" => "DOMESTIC PORT MANAGEMENT",
        "facilities" => "FACILITIES MANAGEMENT",
        "financial" => "FINANCIAL MANAGEMENT",
        "generating" => "GENERATING INTERNAL AUDIT REPORTS AND DOCUMENTATION",
        "green" => "GREEN PORT TECHNOLOGY MANAGEMENT",
        "sps" => "HR DEVELOPMENT (L&D, SUCCESSION PLANNING AND SUCCESSION MANAGEMENT)",
        "hr_dev" => "HR DEVELOPMENT (RSP, PM AND RR)",
        "ISM" => "INFORMATION SYSTEMS MANAGEMENT",
        "ITS" => "INFORMATION TECHNOLOGY SUPPORT",
        "IM" => "INFRASTRUCTURE MANAGEMENT",
        "IAIM" => "INTERNAL AUDIT IMPLEMENTATION AND MANAGEMENT",
        "IAP" => "INTERNAL AUDIT PLANNING",
        "IPM" => "INTERNATIONAL PORT MANAGEMENT",
        "ITDA" => "IT DESIGN ARCHITECTURE",
        "ITIM" => "IT INFRASTRUCTURE MANAGEMENT",
        "KM" => "KNOWLEDGE MANAGEMENT",
        "ls_ial" => "LEGAL SERVICES (INVESTIGATION AND LITIGATION)",
        "ls_rad" => "LEGAL SERVICES (REGULATORY AND DOCUMENTATION)",
        "MAE" => "MONITORING AND EVALUATION",
        "telecom" => "NETWORK, TELECOMMUNICATIONS, WIRELESS AND MOBILITY",
        "organizational" => "ORGANIZATIONAL AND SOCIAL SENSITIVITY",
        "planning" => "PLANNING AND ORGANIZING",
        "port_infra" => "PORT INFRASTRUCTURE DEVELOPMENT AND CONSTRUCTION",
        "PPAD" => "PORT PLANNING AND DESIGN",
        "PPM" => "PORT PROJECT MANAGEMENT",
        "port_service" => "PORT SERVICE PROVIDER MANAGEMENT",
        "PSLD" => "PORT STAKEHOLDERS' LEARNING AND DEVELOPMENT",
        "PME" => "PRIVATIZATION MONITORING AND EVALUATION",
        "PSM" => "PROBLEM SOLVING AND MAKING DECISIONS",
        "PM" => "PROCESS MANAGEMENT",
        "PROCUREMENT" => "PROCUREMENT MANAGEMENT",
        "PUBLIC" => "PUBLIC RELATIONS",
        "RECORDS" => "RECORDS AND DOCUMENTS MANAGEMENT",
        "REPAIR" => "REPAIR AND MAINTENANCE OF PORT FACILITIES",
        "REVENUE" => "REVENUE GENERATION",
        "SUPPLY" => "SUPPLY AND PROPERTY MANAGEMENT",
        "SURVEY" => "SURVEY ADMINISTRATION",
        "tech_writing" => "TECHNICAL WRITING",
        "usetech" => "USE OF TECHNOLOGY",
        // Added adv_ keys
        "adv_owc" => "ORAL AND WRITTEN COMMUNICATION",
        "adv_dse" => "DELIVERING SERVICE EXCELLENCE",
        "adv_exi" => "EXEMPLIFYING INTEGRITY",
        "adv_tsc" => "THINKING STRATEGICALLY AND CREATIVELY",
        "adv_lc" => "LEADING CHANGE",
        "adv_collaborative" => "COLLABORATIVE, INCLUSIVE, WORKING",
        "adv_performance" => "PERFORMANCE AND COACHING FOR RESULTS",
        "adv_nurturing" => "NURTURING A HIGH PERFORMING ORGANIZATION",
        "adv_administrative" => "ADMINISTRATIVE SERVICES MANAGEMENT",
        "adv_assurance" => "ASSURANCE AND IMPROVEMENT OF INTERNAL AUDIT",
        "adv_board" => "BOARD SECRETARIAT MANAGEMENT",
        "adv_budget" => "BUDGET MANAGEMENT",
        "adv_business" => "BUSINESS DEVELOPMENT",
        "adv_cash" => "CASH MANAGEMENT",
        "adv_change" => "CHANGE ADAPTATION",
        "adv_compensation" => "COMPENSATION AND BENEFITS",
        "adv_conducting" => "CONDUCTING AUDIT ASSIGNMENTS",
        "adv_conflict" => "CONFLICT RESOLUTION",
        "adv_data" => "DATA SECURITY MANAGEMENT",
        "adv_domestic" => "DOMESTIC PORT MANAGEMENT",
        "adv_facilities" => "FACILITIES MANAGEMENT",
        "adv_financial" => "FINANCIAL MANAGEMENT",
        "adv_generating" => "GENERATING INTERNAL AUDIT REPORTS AND DOCUMENTATION",
        "adv_green" => "GREEN PORT TECHNOLOGY MANAGEMENT",
        "adv_sps" => "HR DEVELOPMENT (L&D, SUCCESSION PLANNING AND SUCCESSION MANAGEMENT)",
        "adv_hrdev" => "HR DEVELOPMENT (RSP, PM AND RR)",
        "adv_ism" => "INFORMATION SYSTEMS MANAGEMENT",
        "adv_its" => "INFORMATION TECHNOLOGY SUPPORT",
        "adv_im" => "INFRASTRUCTURE MANAGEMENT",
        "adv_aim" => "INTERNAL AUDIT IMPLEMENTATION AND MANAGEMENT",
        "adv_iap" => "INTERNAL AUDIT PLANNING",
        "adv_ipm" => "INTERNATIONAL PORT MANAGEMENT",
        "adv_itda" => "IT DESIGN ARCHITECTURE",
        "adv_itim" => "IT INFRASTRUCTURE MANAGEMENT",
        "adv_km" => "KNOWLEDGE MANAGEMENT",
        "adv_ls_ial" => "LEGAL SERVICES (INVESTIGATION AND LITIGATION)",
        "adv_ls_rad" => "LEGAL SERVICES (REGULATORY AND DOCUMENTATION)",
        "adv_mae" => "MONITORING AND EVALUATION",
        "adv_telecom" => "NETWORK, TELECOMMUNICATIONS, WIRELESS AND MOBILITY",
        "adv_organizational" => "ORGANIZATIONAL AND SOCIAL SENSITIVITY",
        "adv_planning" => "PLANNING AND ORGANIZING",
        "adv_port_infra" => "PORT INFRASTRUCTURE DEVELOPMENT AND CONSTRUCTION",
        "adv_ppad" => "PORT PLANNING AND DESIGN",
        "adv_ppm" => "PORT PROJECT MANAGEMENT",
        "adv_port_service" => "PORT SERVICE PROVIDER MANAGEMENT",
        "adv_psld" => "PORT STAKEHOLDERS' LEARNING AND DEVELOPMENT",
        "adv_pme" => "PRIVATIZATION MONITORING AND EVALUATION",
        "adv_psm" => "PROBLEM SOLVING AND MAKING DECISIONS",
        "adv_pm" => "PROCESS MANAGEMENT",
        "adv_procurement" => "PROCUREMENT MANAGEMENT",
        "adv_public" => "PUBLIC RELATIONS",
        "adv_records" => "RECORDS AND DOCUMENTS MANAGEMENT",
        "adv_repair" => "REPAIR AND MAINTENANCE OF PORT FACILITIES",
        "adv_revenue" => "REVENUE GENERATION",
        "adv_supply" => "SUPPLY AND PROPERTY MANAGEMENT",
        "adv_survey" => "SURVEY ADMINISTRATION",
        "adv_tech_writing" => "TECHNICAL WRITING",
        "adv_usetech" => "USE OF TECHNOLOGY",
    ];
    

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $first_name = $_POST["first_name"];
    $surname = $_POST["surname"];

    // Construct SQL queries based on form inputs
    $sql = "SELECT `ppa_id`, `item_no`, `plant_pos`, `jg`, `surname`, `first_name`, `middle_name`, 
    `originating_office`, `designated_office`, `status`, `gender`, `birth_date`, `designation`, 
    `date_designation`, `owc`, `dse`, `exi`, `tsc`, `lc`, `collaborative`, `performance`, 
    `nurturing`, `administrative`, `assurance`, `board`, `budget`, `business`, `cash`, 
    `change`, `compensation`, `conducting`, `conflict`, `data`, `domestic`, `facilities`, 
    `financial`, `generating`, `green`, `sps`, `hr_dev`, `ISM`, `ITS`, `IM`, `IAIM`, 
    `IAP`, `IPM`, `ITDA`, `ITIM`, `KM`, `ls_ial`, `ls_rad`, `MAE`, `telecom`, 
    `organizational`, `planning`, `port_infra`, `PPAD`, `PPM`, `port_service`, 
    `PSLD`, `PME`, `PSM`, `PM`, `PROCUREMENT`, `PUBLIC`, `RECORDS`, `REPAIR`, 
    `REVENUE`, `SUPPLY`, `SURVEY`, `tech_writing`, `usetech` 
    FROM comp
    WHERE first_name LIKE '%$first_name%' AND surname LIKE '%$surname%'";

    $sql1 = "SELECT `adv_owc`, `adv_dse`, `adv_exi`, `adv_tsc`, `adv_lc`, `adv_collaborative`, 
                     `adv_performance`, `adv_nurturing`, `adv_administrative`, `adv_assurance`, 
                     `adv_board`, `adv_budget`, `adv_business`, `adv_cash`, `adv_change`, 
                     `adv_compensation`, `adv_conducting`, `adv_conflict`, `adv_data`, 
                     `adv_domestic`, `adv_facilities`, `adv_financial`, `adv_generating`, 
                     `adv_green`, `adv_sps`, `adv_hrdev`, `adv_ism`, `adv_its`, `adv_im`, 
                     `adv_iaim`, `adv_iap`, `adv_ipm`, `adv_itda`, `adv_itim`, `adv_km`, 
                     `adv_ls_ial`, `adv_ls_rad`, `adv_mae`, `adv_telecom`, `adv_organizational`, 
                     `adv_planning`, `adv_port_infra`, `adv_ppad`, `adv_ppm`, `adv_port_service`, 
                     `adv_psld`, `adv_pme`, `adv_psm`, `adv_pm`, `adv_procurement`, `adv_public`, 
                     `adv_records`, `adv_repair`, `adv_revenue`, `adv_supply`, `adv_survey`, 
                     `adv_tech_writing`, `adv_usetech`
              FROM comp 
              WHERE first_name LIKE '%$first_name%' AND surname LIKE '%$surname%'";

     $sql2 = "SELECT p.comp_gap, p.type, p.rating, p.met_notmet
         FROM pmf p
         INNER JOIN comp c 
           ON p.first_name = c.first_name 
          AND p.surname = c.surname
         WHERE c.first_name LIKE '%$first_name%' 
           AND c.surname LIKE '%$surname%'";

    // Query to get counts for pie chart
    $sql3 = "SELECT p.met_notmet, COUNT(*) as count
         FROM pmf p
         INNER JOIN comp c 
           ON p.first_name = c.first_name 
          AND p.surname = c.surname
         WHERE c.first_name LIKE '%$first_name%' 
           AND c.surname LIKE '%$surname%'
         GROUP BY p.met_notmet";

    // Query for rating distribution
    $sql4 = "SELECT p.rating, COUNT(*) as count
         FROM pmf p
         INNER JOIN comp c 
           ON p.first_name = c.first_name 
          AND p.surname = c.surname
         WHERE c.first_name LIKE '%$first_name%' 
           AND c.surname LIKE '%$surname%'
         GROUP BY p.rating
         ORDER BY p.rating";

    // Execute all SQL queries
    $result = $conn->query($sql);
    $result1 = $conn->query($sql1);
    $result2 = $conn->query($sql2);
    $result3 = $conn->query($sql3);
    $result4 = $conn->query($sql4);

    // Check if the first query was successful
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    
    // Prepare chart data for met/not met
    $chartData = [];
    $chartLabels = [];
    $chartColors = [];
    
    if ($result3 && $result3->num_rows > 0) {
        while ($row = $result3->fetch_assoc()) {
            $status = $row['met_notmet'];
            $count = $row['count'];
            
            $chartLabels[] = $status;
            $chartData[] = $count;
            
            if (strtolower($status) === 'met') {
                $chartColors[] = '#10B981';
            } else {
                $chartColors[] = '#EF4444';
            }
        }
    }

    // Prepare rating distribution data
    $ratingData = [];
    $ratingLabels = [];
    $ratingColors = ['#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444', '#10B981'];
    
    if ($result4 && $result4->num_rows > 0) {
        $colorIndex = 0;
        while ($row = $result4->fetch_assoc()) {
            $ratingLabels[] = $row['rating'];
            $ratingData[] = $row['count'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPA Competency Based Monitoring System</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'cinzel': ['Cinzel', 'serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'pulse-slow': 'pulse 2s infinite',
                    }
                }
            }
        }

        function printResults() {
            window.print();
        }

        function toggleView(viewType) {
            const cardView = document.getElementById('cardView');
            const tableView = document.getElementById('tableView');
            const cardBtn = document.getElementById('cardBtn');
            const tableBtn = document.getElementById('tableBtn');

            if (viewType === 'card') {
                cardView.classList.remove('hidden');
                tableView.classList.add('hidden');
                cardBtn.classList.add('bg-blue-600', 'text-white');
                cardBtn.classList.remove('bg-gray-200', 'text-gray-700');
                tableBtn.classList.add('bg-gray-200', 'text-gray-700');
                tableBtn.classList.remove('bg-blue-600', 'text-white');
            } else {
                cardView.classList.add('hidden');
                tableView.classList.remove('hidden');
                tableBtn.classList.add('bg-blue-600', 'text-white');
                tableBtn.classList.remove('bg-gray-200', 'text-gray-700');
                cardBtn.classList.add('bg-gray-200', 'text-gray-700');
                cardBtn.classList.remove('bg-blue-600', 'text-white');
            }
        }
    </script>
    <style>
        @media print {
            form, nav, .no-print { display: none !important; }
            .print-break { page-break-before: always; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen font-inter text-gray-800">
    <!-- Background Pattern -->
    <div class="fixed inset-0 z-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23000000" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>'); background-size: 60px 60px;"></div>
    </div>

    
   
    
    <!-- Main Container -->
    <div class="relative z-10 max-w-7xl mx-auto mt-20 mb-12 p-10">
        <!-- Header Card -->
        <div class="glassmorphism rounded-3xl shadow-2xl p-8 mb-8 animate-fade-in">
            <!-- Logo -->
            <div class="absolute top-5 right-8">
                <img src="logo.png" alt="PPA Logo" class="w-20 h-auto rounded-xl shadow-lg transition-transform duration-300 hover:scale-110 hover:rotate-3">
            </div>
            
            <!-- Title -->
            <h1 class="font-cinzel text-slate-700 text-5xl mb-2 text-center font-bold mr-24 drop-shadow-sm">
                PPA Competency Based Monitoring System
            </h1>
            <p class="text-center text-gray-600 text-lg mr-24 font-light">Performance Tracking & Analytics Dashboard</p>
        </div>
        
        <!-- Search Form -->
        <form method="post" action="combine.php?page=comp" class="glassmorphism p-8 rounded-2xl mb-8 shadow-xl no-print animate-slide-up">
              
            
            <div class="flex flex-wrap items-end gap-6 mb-8">
                <div class="flex-1 min-w-60">
                    <label for="first_name" class="block mb-3 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        First Name:
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           required 
                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                           class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl text-sm transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-xl focus:scale-105 bg-white/80 backdrop-blur-sm">
                </div>
                
                <div class="flex-1 min-w-60">
                    <label for="surname" class="block mb-3 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Surname:
                    </label>
                    <input type="text" 
                           id="surname" 
                           name="surname" 
                           required 
                           value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>"
                           class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl text-sm transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-xl focus:scale-105 bg-white/80 backdrop-blur-sm">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 justify-center">
                <input type="submit" 
                       value="🔍 Search"
                       class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-4 border-none rounded-xl text-sm font-bold cursor-pointer transition-all duration-300 hover:from-blue-600 hover:to-indigo-700 hover:-translate-y-2 hover:rotate-1 shadow-lg hover:shadow-2xl uppercase tracking-wider">
                
                <input type="button" 
                       value="🖨️ Print" 
                       onclick="printResults()"
                       class="bg-gradient-to-r from-gray-500 to-gray-700 text-white px-8 py-4 border-none rounded-xl text-sm font-bold cursor-pointer transition-all duration-300 hover:from-gray-600 hover:to-gray-800 hover:-translate-y-2 hover:rotate-1 shadow-lg hover:shadow-2xl uppercase tracking-wider">
                
                <input type="button" 
                      onclick="location.href='combine.php?page=gaps';"
                       value="❌ Not Met"
                       class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-8 py-4 border-none rounded-xl text-sm font-bold cursor-pointer transition-all duration-300 hover:from-red-600 hover:to-pink-700 hover:-translate-y-2 hover:rotate-1 shadow-lg hover:shadow-2xl uppercase tracking-wider">
                
                <input type="button" 
                       onclick="location.href='combine.php?page=met';"
                       value="✅ Met"
                       class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 border-none rounded-xl text-sm font-bold cursor-pointer transition-all duration-300 hover:from-green-600 hover:to-emerald-700 hover:-translate-y-2 hover:rotate-1 shadow-lg hover:shadow-2xl uppercase tracking-wider">
            </div>
        </form>

        <!-- Employee Info Summary Card -->
        <?php if (isset($result) && $result->num_rows > 0): 
            $result->data_seek(0); // Reset result pointer
            $employeeData = $result->fetch_assoc();
        ?>
        <div class="glassmorphism p-8 rounded-2xl mb-8 shadow-xl animate-slide-up">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                Employee Profile
            </h2>
         
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl">
                    <div class="text-sm text-gray-600 font-medium">Plantilla Position</div>
                    <div class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($employeeData['plant_pos']); ?></div>
                </div>
                <div class="bg-white/60 backdrop-blur-sm p-4 rounded-xl">
                    <div class="text-sm text-gray-600 font-medium">Designation</div>
                    <div class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($employeeData['designation']); ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Analytics Dashboard -->
        <?php if (!empty($chartData) && !empty($chartLabels)): ?>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            <!-- Status Overview Card -->
            <div class="glassmorphism p-6 rounded-2xl shadow-xl animate-slide-up">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    Status Overview
                </h3>
                <div style="height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Progress Metrics -->
            <div class="glassmorphism p-6 rounded-2xl shadow-xl animate-slide-up">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    Progress Metrics
                </h3>
                
                <?php
                $total = array_sum($chartData);
                $metIndex = array_search('met', array_map('strtolower', $chartLabels));
                $metCount = $metIndex !== false ? $chartData[$metIndex] : 0;
                $progressPercentage = $total > 0 ? round(($metCount / $total) * 100, 1) : 0;
                ?>
                
                <!-- Progress Circle -->
                <div class="flex items-center justify-center mb-6">
                    <div class="relative w-32 h-32">
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                            <circle cx="60" cy="60" r="50" fill="none" stroke="url(#gradient)" stroke-width="8" 
                                    stroke-linecap="round" stroke-dasharray="<?php echo $progressPercentage * 3.14; ?> 314"/>
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#10B981"/>
                                    <stop offset="100%" style="stop-color:#3B82F6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-bold text-gray-800"><?php echo $progressPercentage; ?>%</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="space-y-3">
                    <?php for ($i = 0; $i < count($chartLabels); $i++): 
                        $percentage = $total > 0 ? round(($chartData[$i] / $total) * 100, 1) : 0;
                        $isPositive = strtolower($chartLabels[$i]) === 'met';
                    ?>
                    <div class="flex justify-between items-center p-3 bg-white/60 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full <?php echo $isPositive ? 'bg-green-500' : 'bg-red-500'; ?>"></div>
                            <span class="font-medium text-gray-700"><?php echo ucfirst(htmlspecialchars($chartLabels[$i])); ?></span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-gray-800"><?php echo $chartData[$i]; ?></span>
                            <span class="text-sm text-gray-600 ml-1">(<?php echo $percentage; ?>%)</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Rating Distribution -->
            <?php if (!empty($ratingData) && !empty($ratingLabels)): ?>
            <div class="glassmorphism p-6 rounded-2xl shadow-xl animate-slide-up">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    Rating Distribution
                </h3>
                <div style="height: 250px;">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- View Toggle -->
        <?php if (isset($result2) && $result2->num_rows > 0): ?>
        <div class="glassmorphism p-6 rounded-2xl shadow-xl mb-8 animate-slide-up">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    Performance Monitoring Details
                </h3>
                
                <!-- View Toggle Buttons -->
                <div class="flex bg-gray-200 rounded-xl p-1 no-print">
                    <button id="cardBtn" onclick="toggleView('card')" 
                            class="px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300 bg-blue-600 text-white">
                        Card View
                    </button>
                    <button id="tableBtn" onclick="toggleView('table')" 
                            class="px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300 bg-gray-200 text-gray-700">
                        Table View
                    </button>
                </div>
            </div>

            <!-- Card View -->
            <div id="cardView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $result2->data_seek(0); // Reset result pointer
                while ($row = $result2->fetch_assoc()) {
                    $isPositive = strtolower($row['met_notmet']) === 'met';
                    $statusColor = $isPositive ? 'from-green-400 to-green-600' : 'from-red-400 to-red-600';
                    $bgColor = $isPositive ? 'border-green-200 bg-green-50/50' : 'border-red-200 bg-red-50/50';
                    $textColor = $isPositive ? 'text-green-800' : 'text-red-800';
                ?>
                <div class="bg-white/80 backdrop-blur-sm border-2 <?php echo $bgColor; ?> rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-lg mb-2 leading-tight">
                                <?php echo htmlspecialchars($row['comp_gap']); ?>
                            </h4>
                            <span class="inline-block px-3 py-1 text-xs font-medium bg-gray-200 text-gray-700 rounded-full">
                                <?php echo htmlspecialchars($row['type']); ?>
                            </span>
                        </div>
                        <div class="ml-3 text-right">
                            <div class="text-2xl font-bold text-gray-800 mb-1">
                                <?php echo htmlspecialchars($row['rating']); ?>
                            </div>
                            <div class="text-xs text-gray-600">Rating</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="<?php echo $textColor; ?> font-bold text-sm uppercase tracking-wide">
                            <?php echo htmlspecialchars($row['met_notmet']); ?>
                        </span>
                        <div class="w-12 h-12 bg-gradient-to-r <?php echo $statusColor; ?> rounded-full flex items-center justify-center">
                            <?php if ($isPositive): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <!-- Table View -->
            <div id="tableView" class="hidden overflow-hidden rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full bg-white">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <th class="px-6 py-4 text-left font-bold text-gray-700 border-b">Competency</th>
                                <th class="px-6 py-4 text-left font-bold text-gray-700 border-b">Type</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-700 border-b">Rating</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-700 border-b">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result2->data_seek(0); // Reset result pointer
                            $rowIndex = 0;
                            while ($row = $result2->fetch_assoc()) {
                                $isPositive = strtolower($row['met_notmet']) === 'met';
                                $statusBg = $isPositive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                $rowBg = $rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50/50';
                                $rowIndex++;
                            ?>
                            <tr class="<?php echo $rowBg; ?> hover:bg-blue-50/50 transition-colors duration-200">
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="font-medium text-gray-800"><?php echo htmlspecialchars($row['comp_gap']); ?></div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <span class="inline-block px-3 py-1 text-xs font-medium bg-gray-200 text-gray-700 rounded-full">
                                        <?php echo htmlspecialchars($row['type']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    <div class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($row['rating']); ?></div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    <span class="inline-block px-4 py-2 text-sm font-bold <?php echo $statusBg; ?> rounded-full uppercase tracking-wide">
                                        <?php echo htmlspecialchars($row['met_notmet']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Competencies Sections (Collapsible) -->
        <!-- Competencies Sections -->
        <div class="space-y-8 mt-8">
            <?php
            // Display Required Competencies
            if (isset($result) && $result->num_rows > 0) {
                $result->data_seek(0); // Reset result pointer
                echo "<div class='glassmorphism p-8 rounded-2xl shadow-xl animate-slide-up'>";
                echo "<h3 class='text-2xl font-bold text-gray-800 flex items-center gap-3 mb-6'>";
                echo "<div class='w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg'>";
                echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 text-white' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z' />";
                echo "</svg>";
                echo "</div>";
                echo "Required Competencies";
                echo "</h3>";
                
                echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
                        if (!empty($value)) {
                            $displayKey = array_key_exists($key, $fieldLabels) ? $fieldLabels[$key] : ucwords(str_replace("_", " ", $key));
                            echo "<div class='bg-gradient-to-br from-white/80 to-blue-50/50 backdrop-blur-sm p-4 rounded-xl border-2 border-blue-100 hover:border-blue-300 hover:shadow-lg transition-all duration-300'>";
                            echo "<div class='text-xs font-bold text-blue-600 uppercase tracking-wider mb-2 break-words'>$displayKey</div>";
                            echo "<div class='text-sm text-gray-800 font-medium break-words'>$value</div>";
                            echo "</div>";
                        }
                    }
                }
                echo "</div>";
                echo "</div>";
            }
            
            // Display Advanced Competencies  
            if (isset($result1) && $result1->num_rows > 0) {
                echo "<div class='glassmorphism p-8 rounded-2xl shadow-xl animate-slide-up'>";
                echo "<h3 class='text-2xl font-bold text-gray-800 flex items-center gap-3 mb-6'>";
                echo "<div class='w-10 h-10 bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg'>";
                echo "<svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5 text-white' fill='none' viewBox='0 0 24 24' stroke='currentColor'>";
                echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 10V3L4 14h7v7l9-11h-7z' />";
                echo "</svg>";
                echo "</div>";
                echo "Advanced Competencies";
                echo "</h3>";
                
                echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
                while ($row = $result1->fetch_assoc()) {
                    foreach ($row as $key => $value) {
                        if (!empty($value)) {
                            $displayKey = array_key_exists($key, $fieldLabels) ? $fieldLabels[$key] : ucwords(str_replace("_", " ", $key));
                            echo "<div class='bg-gradient-to-br from-white/80 to-indigo-50/50 backdrop-blur-sm p-4 rounded-xl border-2 border-indigo-100 hover:border-indigo-300 hover:shadow-lg transition-all duration-300'>";
                            echo "<div class='text-xs font-bold text-indigo-600 uppercase tracking-wider mb-2 break-words'>$displayKey</div>";
                            echo "<div class='text-sm text-gray-800 font-medium break-words'>$value</div>";
                            echo "</div>";
                        }
                    }
                }
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>

    <script>
        // Toggle sections
        function toggleSection(section) {
            const sectionEl = document.getElementById(section + 'Section');
            const iconEl = document.getElementById(section === 'required' ? 'reqIcon' : 'advIcon');
            
            if (sectionEl.style.display === 'none') {
                sectionEl.style.display = 'block';
                iconEl.style.transform = 'rotate(0deg)';
            } else {
                sectionEl.style.display = 'none';  
                iconEl.style.transform = 'rotate(-90deg)';
            }
        }

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($chartData) && !empty($chartLabels)): ?>
            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($chartLabels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($chartData); ?>,
                        backgroundColor: <?php echo json_encode($chartColors); ?>,
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 12, weight: 'bold' },
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    },
                    animation: { duration: 1500 }
                }
            });
            <?php endif; ?>

            <?php if (!empty($ratingData) && !empty($ratingLabels)): ?>
            // Rating Chart
            const ratingCtx = document.getElementById('ratingChart').getContext('2d');
            new Chart(ratingCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($ratingLabels); ?>,
                    datasets: [{
                        label: 'Count',
                        data: <?php echo json_encode($ratingData); ?>,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    },
                    animation: { duration: 1500 }
                }
            });
            <?php endif; ?>
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>