<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata for character set and viewport settings for responsiveness -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Link to external CSS stylesheet for styling the landing page -->
    <link rel="stylesheet" href="style/landing-page.css">
    
    <!-- Title of the web page displayed in the browser tab -->
    <title>G2P MVC Application</title>
</head>
<body>
    <!-- Header section containing the main heading of the page -->
    <header>
        <h1>G2P MVC Application</h1>
    </header>
    
    <!-- Main content section of the landing page -->
    <main>
        <section class="landing-section">
            <!-- Subheading for the landing page -->
            <h2>Welcome to the Game Management System</h2>
            
            <!-- Paragraph providing a brief description of the system -->
            <p>Manage games, players, and assignments with ease.</p>
            
            <!-- Container for buttons linking to different pages in the application -->
            <div class="button-container">
                <!-- Link to the page for adding game categories -->
                <a href="view/add-games.php" class="button">Game Category</a>
                
                <!-- Link to the page for adding player categories -->
                <a href="view/add-players.php" class="button">Player Category</a>
                
                <!-- Link to the page for handling assignments -->
                <a href="controller/assignments.php" class="button">Assignments</a>
            </div>
        </section>
    </main>
    
    <!-- Footer section with copyright information -->
    <footer>
        <p>&copy; 2023 G2P MVC Application. All rights reserved.</p>
    </footer>
</body>
</html>
