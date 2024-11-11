<?php
// Include database configuration to establish a connection
require_once '../config/dbconfig.php';

// MODEL: Database interaction functions

// Function to fetch a player from the database by their ID
function getPlayerById($playerId) {
    global $connectionObject;
    $sql = "SELECT * FROM Players WHERE playerId = '$playerId'"; // SQL query to fetch player data
    $result = mysqli_query($connectionObject, $sql); // Execute query
    return mysqli_fetch_assoc($result); // Return the result as an associative array
}

// Function to update the player details in the database
function updatePlayer($playerId, $playerName, $playerLevel) {
    global $connectionObject;
    $sql = "UPDATE Players SET playerName = '$playerName', selectLevel = '$playerLevel' WHERE playerId = '$playerId'"; // SQL query to update player data
    return mysqli_query($connectionObject, $sql); // Execute update query and return the result
}

// CONTROLLER: Handling POST requests and interacting with the model

$errorMessage = "";  // Variable to store error messages
$successMessage = "";  // Variable to store success messages

// Check if the 'submit' button is clicked to fetch player details
if (isset($_POST['submit'])) {
    $enteredID = $_POST['playerId']; // Get the entered player ID

    // Fetch player details based on the entered ID
    $player = getPlayerById($enteredID);

    // If no player is found, set an error message
    if (!$player) {
        $errorMessage = "No player found with the given ID.";
    }
} elseif (isset($_POST['update'])) { // Check if the 'update' button is clicked to update player details
    $id = $_POST['playerId'];  // Get the player ID
    $updatedName = $_POST['playerName'];  // Get the updated player name
    $updatedLevel = $_POST['playerLevel'];  // Get the updated player level

    // Attempt to update the player details in the database
    if (updatePlayer($id, $updatedName, $updatedLevel)) {
        $successMessage = "Record Updated Successfully!"; // Success message if update is successful
    } else {
        $errorMessage = "Error Occurred: " . mysqli_error($connectionObject); // Error message if update fails
    }
}

// VIEW: The HTML form for displaying and updating the player
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Update Record</title>
<link rel="stylesheet" href="edit-player.css" />  <!-- Link to external CSS for styling -->
</head>
<body>
<div class="form">
    <h1>Update Record</h1>

    <!-- Display any error message in red color -->
    <p style="color:#FF0000;"><?php echo isset($errorMessage) ? $errorMessage : ''; ?></p>

    <!-- Display any success message in green color -->
    <p style="color:#00FF00;"><?php echo isset($successMessage) ? $successMessage : ''; ?></p>

    <div>
        <!-- Form to enter the player ID and fetch their details -->
        <form name="form" method="post" action="">
            <input name="playerId" type="text" placeholder="Enter the Id:" required/>
            <input name="submit" type="submit" value="Fetch Details">
        </form>
    </div>

    <?php if (isset($player)): ?> <!-- If player details are fetched, display them in a form for editing -->
    <form method="post" action="">
        Player ID <input type="text" name="playerId" value="<?php echo $player['playerId']; ?>" readonly> <!-- Player ID field (read-only) -->
        Player Name <input type="text" name="playerName" value="<?php echo $player['playerName']; ?>"> <!-- Player Name field -->
        Player Level <input type="text" name="playerLevel" value="<?php echo $player['selectLevel']; ?>"> <!-- Player Level field -->
        <input type="submit" value="Update" name="update"> <!-- Submit button to update the player details -->
    </form>
    <?php endif; ?>
</div>
</body>
</html>

<?php
// Close the database connection to free up resources
mysqli_close($connectionObject);
?>
