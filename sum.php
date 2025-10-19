<?php
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


$tableHeaders = array(
    
    "reqc" => "REQUIRED COMPETENCIES",
    "metc" => "MET COMPETENCIES",
    "tgaps" => "TOTAL GAPS",
    
   
);


// Handle form submission

$sql2 = "SELECT `reqc`, `metc`, `tgaps` FROM `gaps`  WHERE reqc= '31182'";



$result2 = $conn->query($sql2);


if (!$result2) {
    die("Query Failed: " . $conn->error);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script>
function printResults() {
        window.print();
    }
    </script>
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUMMARY</title>
    
    <style>
        body{
    background-color: #f0f0f0;
    font-family: 'Prata', serif;
    margin:0;
}
.container {
    max-width: 1250px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
}

table {
        width: 100%; /* Set table width to 100% */
        border-collapse: collapse;
        border: 2px solid white;
        margin-bottom: 30px;
        margin-right: auto;
        margin-left: auto;
        padding: 50px;
    }

    th, td {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px;
        text-align: center;
        width: calc(100% / 9);
       
    }

    th {
       
        width: calc(100% / 9);
        background-color: #1b4965;
        color: white;
        text-align: center;
    }
        
        form{
            background-color: #f9f9f9;
            text-align: center;
            font-size:25px;
            max-width:670px;
            margin-top: 10px;
            margin-bottom: 50px;
            margin-right:auto;
            margin-left:auto;
            border-radius:8px;
            border:none;
            font-family: 'Cinzel', serif;
        }
h2{
    text-align: center;
    color:#1b4965;
    font-size: 35px;
    font-family: 'Calibri', serif;
}
input[type="button"]:hover {
    background-color: #03045e;
}
input[type="submit"]:hover {
    background-color: #03045e;
}
input[type="submit"] {
    background-color: #ffd500;
    margin: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 8px 20px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size:15px;
    font-weight: bolder;
    margin-bottom:20px;
}
input[type="button"] {
    margin: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 8px 20px;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #a4161a;
    font-size:15px;
    font-weight: bolder;
}
.logo {
    
    margin-bottom: 20px;
    margin-right:50px;
    float:left;
}

.logo img {
    position: absolute;
  top: 8px;
  right: 16px;
  font-size: 18px;
   max-width:15%; 
    height: auto; 
    border-radius: 8px;
    
}

   
   
   

.summary1-container {
   
    margin-top: 20px;
    margin-left:auto;
    margin-right:auto;
    border: 1px solid #ccc;
    background-color: #f0f0f0;
    padding: 10px;
    width:600px;
    border-radius: 8px; 
   

   
}

.summary-header {
    
    color:red;
    max-width:auto;
    font-family: 'Arial', serif;
}

.summary-list {
    list-style-type: none;
    padding: 0;
    font-family: 'calibri', serif;
}

.summary-item {
    margin-bottom: 5px;
}
#blue {
    background-color: blue; 
    color: white;
    font-size:15px;
    font-weight: bolder;
}
#green {
    background-color: green; 
    color: white;
    font-size:14px;
    font-weight: bolder;
}
@media print {
    form { display: none; }
    h2{ display: none; }
    
    
}
    </style>
    
     <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
</head>
<body>





    <div class="container">

        
        
        <!-- Form to adjust SQL query -->
        <form method="post">
        <h2>Competencies Profile Summary Report</h2>
   
    <input type="submit" value="Submit">
    <input type="button" value="Print" onclick="printResults()">
    <input type="button" id="blue" onclick="location.href='comp.php';" value="Return" />
    <input type="button" id="green" onclick="location.href='gaps.php';" value="NOT MET" />
    <input type="button" id="green" onclick="location.href='met.php';" value="MET" />
    
</form>

        <?php
        
        // Display data if query was executed
       
        if (isset($result2) && $result2->num_rows > 0) {
            echo "<div class='summary1-container'>";
            echo "<p class='summary-header'>COMPETENCY PROFILE SUMMARY:</p>";
            echo "<ul class='summary-list'>";
            echo "<table>";
            echo "<tr><th>TOTAL REQUIRED COMPETENCIES</th><th>TOTAL MET COMPETENCIES</th><th>TOTAL GAPS</th></tr>";
            while ($row = $result2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["reqc"] . "</td>";
                echo "<td>" . $row["metc"] . "</td>";
                echo "<td>" . $row["tgaps"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "No results found.";
        }
    

        ?>
        
        
        

    </div>
    

</body>
</html>

<?php



// Close connection
$conn->close();
?>