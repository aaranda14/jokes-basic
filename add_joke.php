<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "Only logged in users may access this page. Click <a href='login_form.php'>here</a> to login<br>";
    exit;
}

include "db_connect.php";

$new_joke_question = addslashes($_GET['newjoke']);
$new_joke_answer = addslashes($_GET['jokeanswer']);
$userid = $_SESSION['userid'];

echo "<h2>Trying to add a new joke " . htmlspecialchars($new_joke_question) . " and " . htmlspecialchars($new_joke_answer) . "</h2>";

// Prepare the SQL statement
$stmt = $mysqli->prepare("INSERT INTO jokes_table (Joke_question, Joke_answer, user_id) VALUES (?, ?, ?)");

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

// Bind the parameter
$stmt->bind_param("ssi", $new_joke_question, $new_joke_answer, $userid);

// Execute the statement
$stmt->execute();
$stmt->close();

include "search_all_jokes.php";

echo "<a href='index.php'>Return to main</a>";
?>
