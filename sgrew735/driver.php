<?php
session_start();
require_once('./data/config.inc.php');
require_once('./data/DatabaseHelper.php');

include('./includes/header.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/data/f1.db']);

?>

<div class="container">
    <?php
    if (!empty($_GET['driverId'])) {
        $driverId = $_GET['driverId'];

        $driverStmt = $pdo->prepare("
            SELECT drivers.forename, drivers.surname, drivers.dob, drivers.nationality, drivers.url
            FROM drivers
            WHERE drivers.driverId = ?
        ");
        $driverStmt->execute([$driverId]); 
        $driver = $driverStmt->fetch();

        if ($driver) {
            $dob = new DateTime($driver['dob']);
            $now = new DateTime();
            $age = $now->diff($dob)->y;

            echo '<h1>' . $driver['forename'] . ' ' . $driver['surname'] . '</h1>';
            echo '<p>Date of Birth: ' . $driver['dob'] . '</p>';
            echo '<p>Age: ' . $age . '</p>';
            echo '<p>Nationality: ' . $driver['nationality'] . '</p>';
            echo '<p>More info: <a href="' . $driver['url'] . '" target="_blank">Wikipedia</a></p>';

            $resultStmt = $pdo->prepare("
                SELECT races.round, races.name AS circuit, results.position, results.points
                FROM results
                INNER JOIN races ON results.raceId = races.raceId
                WHERE results.driverId = ? AND races.year = 2022
                ORDER BY races.round
            ");
            $resultStmt->execute([$driverId]); 
            $results = $resultStmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<h2>Race Results</h2>';
            if ($results) {
                echo '<table>';
                echo '<thead><tr><th>Round</th><th>Circuit</th><th>Position</th><th>Points</th></tr></thead>';
                echo '<tbody>';
                foreach ($results as $result) {
                    echo '<tr>';
                    echo '<td>' . $result['round'] . '</td>';
                    echo '<td>' . $result['circuit'] . '</td>';
                    echo '<td>' . $result['position'] . '</td>';
                    echo '<td>' . $result['points'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p>No results available for this driver.</p>';
            }
        } else {
            echo '<p>Driver not found.</p>';
        }
    } else {
        echo '<p>No driver selected!</p>';
    }
    ?>
</div>

<?php
include('./includes/footer.php');
?>