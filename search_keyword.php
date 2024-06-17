<head>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#accordion" ).accordion();
  } );
  </script>
</head>
<?php

include "db_connect.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');
$keywordfromform = $_GET['keyword'];
echo htmlspecialchars($keywordfromform);

echo "<h2>Show all jokes with the word " . htmlspecialchars($keywordfromform) . "</h2>";
$keywordfromform = "%" . $keywordfromform . "%";

// Prepare the SQL statement
$stmt = $mysqli->prepare("SELECT jokes_table.JokeID, jokes_table.Joke_question, jokes_table.Joke_answer, jokes_table.user_id, users.username FROM jokes_table JOIN users ON users.user_id = jokes_table.user_id WHERE jokes_table.Joke_question LIKE ?");

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

// Bind the parameter
$stmt->bind_param("s", $keywordfromform);

// Execute the statement
$stmt->execute();
$stmt->store_result();

// Bind the result variables
$stmt->bind_result($JokeID, $Joke_question, $Joke_answer, $userid, $username);

if ($stmt->num_rows > 0) {
    // Output data of each row
    echo "<div id='accordion'>";
    while ($stmt->fetch()) {
        $safe_joke_question = htmlspecialchars($Joke_question);
        $safe_joke_answer = htmlspecialchars($Joke_answer);

        echo "<h3>" . $safe_joke_question . "</h3>";
        echo "<div><p>" . $safe_joke_answer . " -- Submitted by user " . htmlspecialchars($username) . "</p></div>";
    }
    echo "</div>";
} else {
    echo "0 results";
}

// Close the statement
$stmt->close();

// Close the database connection
$mysqli->close();
?>
