<?php 
require_once '../config/dbconfig.php';

class PlayerModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Add a new player
    public function addPlayer($player_id, $player_name, $player_level) {
        $stmt = $this->connection->prepare("INSERT INTO Players (playerId, playerName, selectLevel) VALUES (?, ?, ?)");

        if ($stmt === false) {
            echo "SQL error: " . $this->connection->error;
            return false;
        }

        $stmt->bind_param("iss", $player_id, $player_name, $player_level);

        if ($stmt->execute() === false) {
            echo "Execution Failed: " . $stmt->error;
            return false;
        }

        echo "<script type='text/javascript'>alert('New player added successfully!');</script>";

        $stmt->close();
        return true;
    }
}

// Fetching players from database for display
$selectStatement = "SELECT * FROM Players";
$res = mysqli_query($connectionObject, $selectStatement);

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // When adding a player
    if (isset($_POST["add"])) {
        $player_id = $_POST['playerId'];
        $player_name = $_POST['playerName'];
        $player_level = $_POST['playerLevel'];

        $playerModel = new PlayerModel($connectionObject);
        $playerModel->addPlayer($player_id, $player_name, $player_level);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G2P Allocation System</title>
    <link rel="stylesheet" href="../view/add-players.css">
</head>
<body>
    <div class="container">
        <h1>G2P Allocation System</h1>
        
        <!-- Form to submit player data -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="playerId">Player ID:</label>
                <input type="text" id="playerId" name="playerId" placeholder="Enter the Player ID" >
            </div>
            <div class="form-group">
                <label for="playerName">Player Name:</label>
                <input type="text" id="playerName" name="playerName" placeholder="Enter the Player Name" >
            </div>
            
            <div class="form-group">
                <label for="playerLevel">Player Level:</label>
                <input type="text" id="playerLevel" name="playerLevel" placeholder="Enter the player level" >
            </div>
            <div class="button-group">
                <button type="submit" class="view-button" name="view">View Players</button>
                <button type="submit" class="add-button" name="add">Add Player</button>
            </div>
        </form>
        
        <!-- Display the table of players if "View Players" is clicked -->
        <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["view"])) {
            echo '<table width="100%" border="1" style="border-collapse:collapse;">
                <thead>
                    <tr>
                        <th><strong>Player Id</strong></th>
                        <th><strong>Player Name</strong></th>
                        <th><strong>Player Level</strong></th>
                        <th><strong>Edit Data</strong></th>
                        <th><strong>Delete Data</strong></th>
                    </tr>
                </thead>
                <tbody>';

            // Fetch each row using mysqli_fetch_array
            while ($row = mysqli_fetch_array($res)) {
                // Fetch individual columns using both associative and numeric indexes
                $fetchedId = $row["playerId"];
                $fetchedName = $row["playerName"];
                $fetchedLevel = $row["selectLevel"];

                // Display each record inside table row
                echo "<tr style='text-align: center'>";
                echo "<td>$fetchedId</td>";
                echo "<td>$fetchedName</td>";
                echo "<td>$fetchedLevel</td>";
                echo "<td><a href='../model/edit-player.php'>Edit</a></td>";
                echo "<td><a href='../model/delete-player.php'>Delete</a></td>";
                echo "</tr>";
            }

            echo '</tbody></table>';
        }
        ?>
    </div>
</body>
</html>
