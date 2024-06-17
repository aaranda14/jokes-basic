<?php
// Add a new user to the database. Requires input from register_new_user.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_connect.php";
$new_username = $_GET['username'];
$new_password1 = $_GET['password'];
$new_password2 = $_GET['password-confirm'];

echo "<h2>Trying to add a new user " . htmlspecialchars($new_username) . "</h2>";

// Check if the password meets complexity requirements
if (!isPasswordComplex($new_password1)) {
    echo "Password does not meet complexity requirements. Please try again.";
    exit;
}

// Check to see if this username has already been registered.
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $new_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "The username " . htmlspecialchars($new_username) . " is already in use. Try another.";
    exit;
} 
// Check to see if the password fields match
else if ($new_password1 != $new_password2) {
    echo "The passwords do not match. Please try again.";
    exit;
} else {
    // Hash the password before storing it
    $hashed_password = password_hash($new_password1, PASSWORD_DEFAULT);
    
    // Add the new user
    $sql = "INSERT INTO users (user_id, username, password) VALUES (null, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $new_username, $hashed_password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Registration success!";
    } else {
        echo "Something went wrong. Not registered.";
    }
}

echo "<a href='index.php'>Return to main</a>";

// Function to check password complexity
function isPasswordComplex($password) {
    // Define password complexity criteria
    $min_length = 8;
    $has_uppercase = preg_match('/[A-Z]/', $password);
    $has_lowercase = preg_match('/[a-z]/', $password);
    $has_number = preg_match('/\d/', $password);
    $has_special_char = preg_match('/[^A-Za-z0-9]/', $password);

    // Check if all complexity criteria are met
    if (strlen($password) < $min_length || !$has_uppercase || !$has_lowercase || !$has_number || !$has_special_char) {
        return false;
    }

    return true;
}
?>
