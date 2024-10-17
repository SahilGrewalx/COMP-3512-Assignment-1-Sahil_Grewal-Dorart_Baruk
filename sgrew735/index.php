<?php
session_start();
require_once('./data/config.inc.php');
require_once('./data/DatabaseHelper.php');

include('./includes/header.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/data/f1.db', null, null]);

?>

<main>
    <div class="content-container">
        <div class="left-section">
            <h2>About This Project</h2>
            <p>
                Welcome to the F1 Dashboard, a web-based application built to explore the 2022 Formula 1 season. This project was developed by Sahil Grewal and Dorart Barku as part of our coursework for COMP 3512.<br>
                The website showcases data about Formula 1 races, drivers, and circuits for the 2022 season, and offers API functionality for retrieving data in JSON format.<br>
                Technologies used include PHP for server-side scripting, SQLite for the database, and HTML/CSS for the frontend. You can explore the source code on our GitHub repository.
            </p>

            <a href="browse.php" class="browse-btn">Browse 2022 Season</a>
        </div>
        <div class="right-section">
            <img src="Sahil-Dorart.jpg" alt="Sahil and Dorart" width="400">
        </div>
    </div>
</main>

<?php
include('./includes/footer.php');
?>