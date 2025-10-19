<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ppa";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login_username = $_POST["username"];
    $login_password = $_POST["password"];
    
    // Prepare SQL query to check user credentials
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $login_username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($login_password, $hashed_password)) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: comp.php"); // Redirect to comp.php upon successful login
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "No user found with that username.";
        }
        
        $stmt->close();
    } else {
        $login_error = "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Cinzel', serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            background: linear-gradient(130deg, rgba(255, 255, 255, 0.9), rgba(255, 204, 0, 0.8));
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #003366; /* Dark blue */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #003366; /* Dark blue */
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #00509e; /* Lighter blue */
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
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
    </style>
</head>

<body>

<div class="container">

    <div class="logo">
<img src="logo.png">
</div>
<div class="login-container">
        <h2>Competency Based Monitoring System Login</h2>
        <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Login">
        </form>
    
    <!-- This is a comment -->

    </div>
</body>
</html>

<?php
$conn->close();
?>
