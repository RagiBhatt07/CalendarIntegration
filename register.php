<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <title>Register</title>
</head>
<body>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file here
    require 'db_conn.php';

    // Get the form input values
    $s_id = $_POST['s_id']; // Assuming this is how you collect the student ID from the form
    $s_lastname = $_POST['s_lastname'];
    $s_firstname = $_POST['s_firstname'];
    $s_year = $_POST['s_year'];
    $s_email = $_POST['s_email'];
    $s_password = $_POST['s_password']; // You should hash passwords before storing

    // Check for valid email
    if (!filter_var($s_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } else {
        // Check for duplicate student ID
        $id_check_stmt = $mysqli->prepare("SELECT * FROM register WHERE s_id = ?");
        $id_check_stmt->bind_param("i", $s_id);
        $id_check_stmt->execute();
        $id_result = $id_check_stmt->get_result();
        if ($id_result->num_rows > 0) {
            echo "A user with this student ID already exists.";
        } else {
            // No duplicate student ID, continue with email check
            $email_check_stmt = $mysqli->prepare("SELECT * FROM register WHERE s_email = ?");
            $email_check_stmt->bind_param("s", $s_email);
            $email_check_stmt->execute();
            $email_result = $email_check_stmt->get_result();
            if ($email_result->num_rows > 0) {
                echo "A student with this email already exists.";
            } else {
                // Email is also unique, proceed to insert new record
                $sql = "INSERT INTO register (s_id, s_lastname, s_firstname, s_year, s_email, s_password) VALUES (?, ?, ?, ?, ?, ?)";

                if ($stmt = $mysqli->prepare($sql)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt->bind_param("isssss", $s_id, $s_lastname, $s_firstname, $s_year, $s_email, $hashed_password);

                    if ($stmt->execute()) {
                        echo "Registration successful!";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error: " . $mysqli->error;
                }
            }
            $email_check_stmt->close();
        }
        $id_check_stmt->close();
    }

    // Close the database connection
    $mysqli->close();
}
?>


    <nav class="navbar">
        <!-- Navigation bar -->
        <div class="nav-container">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="login.html" class="nav-link">Login</a>
                </li>
                <li class="nav-item">
                    <a href="register.php" class="nav-link active">Register</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Registration form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="max-width: 400px; margin: auto;">
    <h2 style="text-align:center;">Register</h2>
    <div style="display: flex; flex-direction: column; gap: 10px;">
        <input type="text" name="s_id" placeholder="Student ID" required>
        <input type="text" name="s_lastname" placeholder="Last Name" required>
        <input type="text" name="s_firstname" placeholder="First Name" required>
        
        <select type="year" name="s_year" required>
            <option value="" disabled selected>Select your year</option>
            <option value="M1 AI">M1 AI</option>
            <option value="M2 AI">M2 AI</option>
            <option value="M1 DS">M1 DS</option>
            <option value="M2 DS">M2 DS</option>
            <option value="M2 HCI">M2 HCI</option>
        </select>
        
        <input type="email" name="s_email" placeholder="Email" required>
        <input type="password" name="s_password" placeholder="Password" required>
        <button type="submit" style="background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; cursor: pointer;">Register</button>
    </div>
</form>

</body>
</html>
