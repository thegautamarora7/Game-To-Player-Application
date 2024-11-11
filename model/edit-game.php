<?php
// Include database configuration file
require_once '../config/dbconfig.php';

// Define the GameController class
class GameController {
    private $connection; // Store the database connection
    private $errorMessage = ''; // Placeholder for error messages

    // Constructor to initialize the database connection
    public function __construct($connectionObject) {
        $this->connection = $connectionObject;
    }

    // Method to fetch game details by gameId
    public function fetchGameDetails($gameId) {
        // SQL query to select game details based on the gameId
        $sqlSelect = "SELECT * FROM Games WHERE gameId='$gameId'";
        // Execute the query
        $queryRes = mysqli_query($this->connection, $sqlSelect);
        // Check if game exists
        if ($queryRes && mysqli_num_rows($queryRes) > 0) {
            return mysqli_fetch_assoc($queryRes); // Return game details if found
        } else {
            // Set error message if no game is found
            $this->errorMessage = "No game found with the given ID.";
            return null; // Return null if no game is found
        }
    }

    // Method to update game details
    public function updateGame($gameId, $gameName, $gameRounds, $completionTime) {
        // Escape the inputs to prevent SQL injection
        $gameName = mysqli_real_escape_string($this->connection, $gameName);
        $gameRounds = mysqli_real_escape_string($this->connection, $gameRounds);
        $completionTime = mysqli_real_escape_string($this->connection, $completionTime);

        // Ensure that all fields are filled
        if (empty($gameId) || empty($gameName) || empty($gameRounds) || empty($completionTime)) {
            $this->errorMessage = "Please fill in all fields.";
            return false; // Return false if any field is empty
        }

        // SQL query to update game details in the database
        $updateQuery = "UPDATE Games SET gameName='$gameName', gameRounds='$gameRounds', completionTime='$completionTime' WHERE gameId='$gameId'";
        // Execute the update query
        return mysqli_query($this->connection, $updateQuery);
    }

    // Method to retrieve the error message
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    // Method to close the database connection
    public function closeConnection() {
        mysqli_close($this->connection);
    }
}

// Instantiate the GameController class and pass the database connection object
$controller = new GameController($connectionObject);

// Initialize variables
$errorMessage = ''; // Placeholder for success or error messages
$game = null; // Placeholder for game details

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If a gameId is provided and the update button is not clicked
    if (isset($_POST['gameId']) && !isset($_POST['update'])) {
        // Fetch the game details based on the provided gameId
        $gameId = $_POST['gameId'];
        $game = $controller->fetchGameDetails($gameId); // Fetch game details
    } elseif (isset($_POST['update'])) {
        // If update button is clicked, update the game record
        $gameId = $_POST['gameId'];
        $gameName = $_POST['gameName'];
        $gameRounds = $_POST['gameRounds'];
        $completionTime = $_POST['completionTime'];

        // Attempt to update the game record
        $updateSuccess = $controller->updateGame($gameId, $gameName, $gameRounds, $completionTime);
        if ($updateSuccess) {
            // Display success message on successful update
            $errorMessage = "Record Updated Successfully.";
        } else {
            // Display error message if update fails
            $errorMessage = $controller->getErrorMessage() ?: "Error Occurred: " . mysqli_error($connectionObject);
        }
    }
}

// Close the database connection after operations are complete
$controller->closeConnection();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"> <!-- Define the character encoding for the page -->
<title>Update Record</title> <!-- Page title -->
<link rel="stylesheet" href="edit-game.css" /> <!-- Link to the custom CSS for styling -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> <!-- Include Bootstrap CSS -->
</head>
<body>
<div class="form">
    <h1>Update Record</h1>

    <!-- Display error or success messages -->
    <p style="color:#FF0000;"><?php echo $errorMessage; ?></p>

    <div>
        <!-- Form to fetch game details by gameId -->
        <form name="form" method="post" action=""> 
            <input name="gameId" type="text" placeholder="Enter the Id:" required/> <!-- Input field for gameId -->
            <input name="submit" type="submit" value="Fetch Details"> <!-- Button to fetch game details -->
        </form>
    </div>

    <!-- If game details are fetched, show the form to update the game -->
    <?php if ($game): ?>
        <form method="post" action="">
            <!-- Display gameId, gameName, gameRounds, and completionTime with values for editing -->
            Game Id <input type="text" name="gameId" value="<?php echo $game['gameId']; ?>" readonly> 
            Game Name <input type="text" name="gameName" value="<?php echo $game['gameName']; ?>">
            Game Rounds <input type="text" name="gameRounds" value="<?php echo $game['gameRounds']; ?>">
            Completion Time <input type="text" name="completionTime" value="<?php echo $game['completionTime']; ?>">
            <!-- Submit button to update game details -->
            <input type="Submit" value="Update" name="update">
        </form>
    <?php endif; ?>
</div>
</body>
</html>
