<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  <!-- Define character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Ensure responsive design -->
    <title>Document</title>  <!-- Page title -->
    <link rel="stylesheet" href="../model/delete.css">  <!-- Link to external CSS for styling -->
</head>
<body>
    <h1>Delete Record</h1>  <!-- Page header -->

    <p style="color:#FF0000;"></p> <!-- Placeholder for error messages if needed -->

    <div>
        <!-- Form for deleting a game record -->
        <form name="form" method="post" action=""> 
            <!-- Input field for user to enter the ID of the record to delete -->
            <input name="id" type="text" placeholder="Enter the Id" required/> 
            <!-- Submit button to send the delete request -->
            <input name="submit" type="submit" value="Delete" />
        </form>
    </div>
</body>
</html>

<?php
// Include the database configuration file to establish a database connection
require_once '../config/dbconfig.php';

// Check if the form has been submitted with a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    // Get the ID entered by the user
    $playerId = $_POST["id"];

    // Prepare SQL query to check if a game with the given ID exists in the database
    $sql = "SELECT * FROM Games WHERE gameId = ?";
    
    // Check if the statement can be prepared
    if ($stmt = $connectionObject->prepare($sql)) { 
        // Bind the user input to the query (using "i" for integer)
        $stmt->bind_param("i", $playerId); 
        
        // Execute the prepared statement
        $stmt->execute(); 
        
        // Get the result of the query
        $result = $stmt->get_result(); 
        
        // Check if the game exists in the database
        if ($result->num_rows > 0) { 
            // Fetch the game details if the game is found
            $player = $result->fetch_assoc(); 

            // Prepare SQL query to delete the game record
            $deleteSql = "DELETE FROM Games WHERE gameId = ?"; 
            
            // Check if the delete query can be prepared
            if ($deleteStmt = $connectionObject->prepare($deleteSql)) { 
                // Bind the game ID to the delete query
                $deleteStmt->bind_param("i", $playerId); 

                // Execute the delete query
                if ($deleteStmt->execute()) { 
                    // Display a success message if the game is deleted
                    echo "<script type='text/javascript'>alert('Game Deleted Successfully!');</script>";
                } else { 
                    // Display an error message if the deletion fails
                    echo "<script type='text/javascript'>alert('Error!');</script>"; 
                } 
                // Close the delete statement
                $deleteStmt->close(); 
            } 
        } else { 
            // Display a message if no game is found with the given ID
            echo "<script type='text/javascript'>alert('No Data Found!');</script>";
        } 
        // Close the initial statement
        $stmt->close(); 
    } 
}

// Close the database connection after completing the operations
$connectionObject->close(); 
?>
