<?php
session_start();
require_once('./data/config.inc.php');
require_once('./data/DatabaseHelper.php');
include('./includes/header.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/data/f1.db']);

?>

<div class="container">
    <?php
    if (!empty($_GET['constructorId'])) {
        $constructorId = $_GET['constructorId'];

        $constructorStmt = $pdo->prepare("
            SELECT name, nationality
            FROM constructors
            WHERE constructorId = ?
        ");
        $constructorStmt->execute([$constructorId]);
        $constructor = $constructorStmt->fetch();

        if ($constructor) {
            echo '<h1>' . $constructor['name'] . '</h1>';
            echo '<p>Nationality: ' . $constructor['nationality'] . '</p>';

            $resultsStmt = $pdo->prepare("
                SELECT races.round, races.name AS circuit, drivers.forename, drivers.surname, results.position, results.points, results.driverId
                FROM results
                INNER JOIN races ON results.raceId = races.raceId
                INNER JOIN drivers ON results.driverId = drivers.driverId
                WHERE results.constructorId = ? AND races.year = 2022
                ORDER BY races.round
            ");
            $resultsStmt->execute([$constructorId]);
            $results = $resultsStmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<h2>Race Results</h2>';
            if ($results) {
                echo '<table>';
                echo '<thead><tr><th>Round</th><th>Circuit</th><th>Driver</th><th>Position</th><th>Points</th></tr></thead>';
                echo '<tbody>';
                foreach ($results as $result) {
                    echo '<tr>';
                    echo '<td>' . $result['round'] . '</td>';
                    echo '<td>' . $result['circuit'] . '</td>';
                    echo '<td><a href="driver.php?driverId=' . $result['driverId'] . '">' . $result['forename'] . ' ' . $result['surname'] . '</a></td>';
                    echo '<td>' . ($result['position'] ?? 'N/A') . '</td>';
                    echo '<td>' . $result['points'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p>No results available for this constructor.</p>';
            }
        } else {
            echo '<p>Constructor not found.</p>';
        }
    } else {
        echo '<p>No constructor selected!</p>';
    }
    ?>
</div>

<?php
include('./includes/footer.php');
?>
