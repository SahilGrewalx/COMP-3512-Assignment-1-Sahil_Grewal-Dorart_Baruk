<?php
session_start();
require_once('./data/config.inc.php');
require_once('./data/DatabaseHelper.php');

include('./includes/header.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/data/f1.db']);

$raceStmt = $pdo->query("SELECT raceId, round, name FROM races WHERE year = 2022;");
$races = $raceStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="race-list">
        <h2>2022 Races</h2>
        <ul>
            <?php
            if (!empty($races)) {
                foreach ($races as $race): ?>
                    <li>
                        <span>Round <?= $race['round']; ?>: <?= $race['name']; ?></span>
                        <a href="browse.php?raceId=<?= $race['raceId']; ?>" class="results-btn">Results</a>
                    </li>
            <?php endforeach;
            } else {
                echo "<p>No races found for 2022.</p>";
            }
            ?>
        </ul>
    </div>

    <div class="race-details">
        <?php
        if (!empty($_GET['raceId'])) {
            $raceId = $_GET['raceId'];

            $resultStmt = $pdo->prepare("
                SELECT races.name AS raceName, races.round, races.date, races.url,
                       circuits.name AS circuitName, circuits.location, circuits.country,
                       results.*, drivers.forename, drivers.surname, constructors.name AS constructorName,
                       qualifying.q1, qualifying.q2, qualifying.q3
                FROM results
                INNER JOIN drivers ON results.driverId = drivers.driverId
                INNER JOIN constructors ON results.constructorId = constructors.constructorId
                INNER JOIN races ON results.raceId = races.raceId
                INNER JOIN circuits ON races.circuitId = circuits.circuitId
                LEFT JOIN qualifying ON results.raceId = qualifying.raceId AND results.driverId = qualifying.driverId
                WHERE results.raceId = ?
            ");
            $resultStmt->execute([$raceId]);
            $results = $resultStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results && count($results) > 0) {
                echo '<h2>Results for ' . $results[0]['raceName'] . '</h2>';
                echo '<div class="race-info">';
                echo '<p>Round: ' . $results[0]['round'] . '</p>';
                echo '<p>Circuit: ' . $results[0]['circuitName'] . ', ' . $results[0]['location'] . ', ' . $results[0]['country'] . '</p>';
                echo '<p>Date: ' . $results[0]['date'] . '</p>';
                echo '<p>More info: <a href="' . $results[0]['url'] . '" target="_blank">Race Link</a></p>';
                echo '</div>';

                echo '<div class="results-summary">';
                echo '<div class="result-box gold"><h3>1st</h3><p>' . $results[0]['forename'] . ' ' . $results[0]['surname'] . '</p></div>';
                echo '<div class="result-box silver"><h3>2nd</h3><p>' . $results[1]['forename'] . ' ' . $results[1]['surname'] . '</p></div>';
                echo '<div class="result-box bronze"><h3>3rd</h3><p>' . $results[2]['forename'] . ' ' . $results[2]['surname'] . '</p></div>';
                echo '</div>';

                echo '<div class="tables-container">';

                echo '<div class="qualifying">';
                echo '<h3>Qualifying</h3>';
                echo '<table>';
                echo '<thead><tr><th>Pos</th><th>Driver</th><th>Constructor</th><th>Q1</th><th>Q2</th><th>Q3</th></thead>';
                echo '<tbody>';
                foreach ($results as $result) {
                    echo '<tr>';
                    echo '<td>' . $result['positionOrder'] . '</td>';
                    echo '<td><a href="driver.php?driverId=' . $result['driverId'] . '">' . $result['forename'] . ' ' . $result['surname'] . '</a></td>';
                    echo '<td><a href="constructor.php?constructorId=' . $result['constructorId'] . '">' . $result['constructorName'] . '</a></td>';
                    echo '<td>' . ($result['q1'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($result['q2'] ?? 'N/A') . '</td>';
                    echo '<td>' . ($result['q3'] ?? 'N/A') . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '</div>';

                echo '<div class="race-results">';
                echo '<h3>Race Results</h3>';
                echo '<table>';
                echo '<thead><tr><th>Pos</th><th>Driver</th><th>Constructor</th><th>Laps</th><th>Points</th></tr></thead>';
                echo '<tbody>';
                foreach ($results as $result) {
                    echo '<tr>';
                    echo '<td>' . $result['positionOrder'] . '</td>';
                    echo '<td><a href="driver.php?driverId=' . $result['driverId'] . '">' . $result['forename'] . ' ' . $result['surname'] . '</a></td>';
                    echo '<td><a href="constructor.php?constructorId=' . $result['constructorId'] . '">' . $result['constructorName'] . '</a></td>';
                    echo '<td>' . $result['laps'] . '</td>';
                    echo '<td>' . $result['points'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                echo '</div>';

                echo '</div>';
            }
        } else {
            echo '<p>Please select a race to view the results.</p>';
        }
        ?>
    </div>
</div>

<?php
include('./includes/footer.php');
?>