<?php 
require_once '../config/dbconfig.php';
$selectStatement = "SELECT * FROM Games";
$res = mysqli_query($connectionObject, $selectStatement);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G2P Allocation System</title>
    <link rel="stylesheet" href="add-games.css">
</head>
<body>
    <div class="container">
        <h1>G2P Allocation System</h1>
        
        <!-- Display success or error message -->

        <!-- Form to submit game data -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="gameId">Game ID:</label>
                <input type="text" id="gameId" name="gameId" placeholder="Enter the Game ID" >
            </div>
            <div class="form-group">
                <label for="gameName">Game Name:</label>
                <input type="text" id="gameName" name="gameName" placeholder="Enter the Game Name" >
            </div>
            <div class="form-group">
                <label for="gameRounds">Number of Rounds:</label>
                <input type="text" id="gameRounds" name="gameRounds" placeholder="Enter the count" >
            </div>
            <div class="form-group">
                <label for="completionTime">Total Completion Time:</label>
                <input type="text" id="completionTime" name="completionTime" placeholder="Enter the completion time">
            </div>
            <div class="button-group">
                <button type="submit" class="view-button" name="view">View Games</button>
                <button type="submit" class="add-button" name="add">Add Game</button>
            </div>
        </form>
        
    </div>

    <!-- Table to display games only when "View Games" is clicked -->
    

</body>
</html>

<?php

require_once '../config/dbconfig.php';

class GameModel {
    private $connection;

    public function __construct() {
        global $connectionObject;
        $this->connection = $connectionObject;
    }

    // Add a new game
    public function addGame($game_id, $game_name, $game_rounds, $completion_time) {
        $stmt = $this->connection->prepare("INSERT INTO Games (gameId, gameName, gameRounds, completionTime) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            echo "SQL error: " . $this->connection->error;
            return false;
        }

        $stmt->bind_param("isis", $game_id, $game_name, $game_rounds, $completion_time);

        if ($stmt->execute() === false) {
            echo "Execution Failed: " . $stmt->error;
            return false;
        }

        echo "<script type='text/javascript'>alert('New game added successfully!');</script>";

        $stmt->close();
        return true;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["view"])) {
    echo '<table width="100%" border="1" style="border-collapse:collapse;">
        <thead>
            <tr>
                <th><strong>Game Id</strong></th>
                <th><strong>Game Name</strong></th>
                <th><strong>Number of Rounds</strong></th>
                <th><strong>Total Completion Time</strong></th>
                
                <th><strong>Edit Data</strong></th>
                <th><strong>Delete Data</strong></th>
            </tr>
        </thead>
        <tbody>';

    // Fetch each row using mysqli_fetch_array
    while ($row = mysqli_fetch_array($res)) {
        // Fetch individual columns using both associative and numeric indexes
        $fetchedId = $row["gameId"];
        $fetchedRounds = $row["gameRounds"];
        $fetchedSports = $row["gameName"];
        $fetchedTime = $row["completionTime"];

        // Display each record inside table row
        echo "<tr style='text-align: center'>";
        echo "<td>$fetchedId</td>";
        echo "<td>$fetchedSports</td>";
        echo "<td>$fetchedRounds</td>";
        echo "<td>$fetchedTime</td>";
       
        echo "<td><a href='../model/edit-game.php'>Edit</a></td>";
        echo "<td><a href='../model/delete-game.php'>Delete</a></td>";
        echo "</tr>";
    }

    echo '</tbody></table>';
}

// Handle POST request for adding a game
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    $gameModel = new GameModel();
    $game_id = $_POST['gameId'];
    $game_name = $_POST['gameName'];
    $game_rounds = $_POST['gameRounds'];
    $completion_time = $_POST['completionTime'];

    $gameModel->addGame($game_id, $game_name, $game_rounds, $completion_time);
}


?>
