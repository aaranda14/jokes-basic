<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jQuery UI Accordion - Default functionality</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
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

// Your SQL query
$sql = "SELECT JokeID, Joke_question, Joke_answer FROM Jokes_table"; // Removed users_id from the query
$result = $mysqli->query($sql);

// Check if the query was successful
if ($result) {
    // Check if there are rows in the result set
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<h3>" . htmlspecialchars($row['Joke_question']) . "</h3>";
            
            // Check if 'users_id' exists, if not, use a default value
            $user_id = isset($row['users_id']) ? htmlspecialchars($row['users_id']) : '1';

            echo "<div><p>" . htmlspecialchars($row["Joke_answer"]) . " submitted by user #" . $user_id . "</p></div>";
        }
    } else {
        echo "0 results";
    }
} else {
    // Display the SQL error
    echo "Error: " . $mysqli->error;
}
?>
