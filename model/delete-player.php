<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  <!-- Define the character encoding for the page -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Ensure responsive design -->
    <link rel="stylesheet" href="../model/delete.css">  <!-- Link to the CSS file for styling -->
    <title>Delete Player Record</title>  <!-- Page title -->
</head>
<body>
    <h1>Delete Player Record</h1>  <!-- Page header -->

    <!-- Display any error messages (if they exist) -->
    <p style="color:#FF0000;"><?php echo $message ?? ''; ?></p>  

    <div>
        <!-- Form for deleting a player record -->
        <form name="form" method="post" action=""> 
            <!-- Input field for the user to enter the player ID to delete -->
            <input name="id" type="text" placeholder="Enter the Player ID" required/> 
            <!-- Submit button to initiate the delete operation -->
            <input name="submit" type="submit" value="Delete" />
        </form>
    </div>
</body>
</html>

<?php
// Include the database configuration file to connect to the database
require_once '../config/dbconfig.php';

$message = '';  // Variable to hold success or error messages

// Check if the form has been submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    // Get the player ID from the form submission
    $playerId = $_POST["id"]; 

    // SQL query to fetch the player details based on the playerId
    $sql = "SELECT * FROM Players WHERE playerId = ?";

    // Prepare the SQL statement to fetch the player
    if ($stmt = $connectionObject->prepare($sql)) {
        // Bind the playerId to the prepared statement
        $stmt->bind_param("i", $playerId); 
        
        // Execute the query
        $stmt->execute(); 
        
        // Get the result of the query
        $result = $stmt->get_result(); 
        
        // Check if the player exists in the database
        if ($result->num_rows > 0) {
            // If the player exists, proceed to delete the player record
            $deleteSql = "DELETE FROM Players WHERE playerId = ?";
            
            // Prepare the SQL statement to delete the player
            if ($deleteStmt = $connectionObject->prepare($deleteSql)) {
                // Bind the playerId to the delete query
                $deleteStmt->bind_param("i", $playerId); 
                
                // Execute the delete query
                if ($deleteStmt->execute()) {
                    // Display success message on successful deletion
                    echo "<script type='text/javascript'>alert('Player Deleted Successfully!');</script>";
                } else {
                    // If there's an error deleting the player, set the error message
                    $message = "Error: Could not delete player!";
                }
                
                // Close the delete statement
                $deleteStmt->close();
            }
        } else {
            // If the player does not exist, display a message
            $message = "No player found with the given ID.";
        }

        // Close the statement after use
        $stmt->close();
    }
}

// Close the database connection after the operations are complete
$connectionObject->close();
?>
