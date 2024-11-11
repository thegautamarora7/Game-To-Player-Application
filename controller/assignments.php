<?php
// Database connection parameters
$servername = "localhost";   // Database server address (localhost for local development)
$username = "root";          // Database username (default 'root' for local)
$password = "";              // Database password (empty for local development)
$dbname = "G2P_Group6";       // Database name

// Create connection to MySQL database using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, terminate script and display error message
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available games for the dropdown
$gamesQuery = "SELECT gameId, gameName FROM Games";  // SQL query to fetch games
$gamesResult = $conn->query($gamesQuery);  // Execute the query

// Fetch available players for the dropdown
$playersQuery = "SELECT playerId, playerName FROM Players";  // SQL query to fetch players
$playersResult = $conn->query($playersQuery);  // Execute the query

// Handle the form submission when the 'assign' button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    // Retrieve the selected game, player, and the current date
    $gameId = $_POST['game'];
    $playerId = $_POST['player'];
    $assignmentDate = date("Y-m-d");  // Get today's date in YYYY-MM-DD format

    // SQL query to insert the assignment into the 'Assignments' table
    $assignQuery = "INSERT INTO Assignments (gameId, playerId, assignmentDate) VALUES ('$gameId', '$playerId', '$assignmentDate')";
    
    // Execute the insert query and check if it was successful
    if ($conn->query($assignQuery) === TRUE) {
        // If successful, show a success message in a JavaScript alert
        echo "<script type='text/javascript'>alert('Assignment Successful!');</script>";
    } else {
        // If error occurred, display error message
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Fetch all assignments for displaying the assignment history
$assignmentsQuery = "
    SELECT Games.gameName, Players.playerName, Assignments.assignmentDate
    FROM Assignments
    JOIN Games ON Assignments.gameId = Games.gameId
    JOIN Players ON Assignments.playerId = Players.playerId
    ORDER BY Assignments.assignmentDate DESC";  // SQL query to get assignments sorted by date
$assignmentsResult = $conn->query($assignmentsQuery);  // Execute the query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  <!-- Define character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Ensure responsive design -->
    <title>G2P Allocation System</title>
    <link rel="stylesheet" href="../controller/assignments.css">  <!-- Link to external CSS file -->
    <script type="text/javascript">
        // JavaScript function to toggle visibility of the assignment details section
        function toggleAssignments() {
            const assignmentsDiv = document.getElementById("assignmentDetails");  // Get the element by ID
            // Toggle display between 'none' (hidden) and 'block' (visible)
            assignmentsDiv.style.display = assignmentsDiv.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">G2P Allocation System</div>  <!-- Header section -->

        <!-- Form to assign a game to a player -->
        <form method="post" action="">
            <label for="game">Select Game:</label>
            <!-- Dropdown to select a game -->
            <select name="game" id="game" required>
                <option value="">Select the game</option>
                <?php while ($game = $gamesResult->fetch_assoc()): ?>
                    <!-- Populate game options dynamically from the database -->
                    <option value="<?= $game['gameId'] ?>"><?= htmlspecialchars($game['gameName']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="player">Select Player:</label>
            <!-- Dropdown to select a player -->
            <select name="player" id="player" required>
                <option value="">Select the player</option>
                <?php while ($player = $playersResult->fetch_assoc()): ?>
                    <!-- Populate player options dynamically from the database -->
                    <option value="<?= $player['playerId'] ?>"><?= htmlspecialchars($player['playerName']) ?></option>
                <?php endwhile; ?>
            </select>

            <!-- Submit button to assign game -->
            <div class="button">
                <button type="submit" name="assign">Assign</button>
                <!-- Button to toggle assignment details visibility -->
                <button type="button" onclick="toggleAssignments()">View Assignments</button>
            </div>
        </form>

        <!-- Section to display assignment details (Initially Hidden) -->
        <div id="assignmentDetails" style="display: none;">
            <h3>Assignment Details</h3>
            <ul>
                <?php if ($assignmentsResult->num_rows > 0): ?>
                    <!-- If assignments exist, display them -->
                    <?php while ($assignment = $assignmentsResult->fetch_assoc()): ?>
                        <!-- Display each assignment with game name, player name, and assignment date -->
                        <li><?= htmlspecialchars($assignment['gameName']) ?> has been assigned to <?= htmlspecialchars($assignment['playerName']) ?> on <?= date("d/m/Y", strtotime($assignment['assignmentDate'])) ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- If no assignments exist, show this message -->
                    <li>No assignments available.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection to free up resources
$conn->close();
?>
