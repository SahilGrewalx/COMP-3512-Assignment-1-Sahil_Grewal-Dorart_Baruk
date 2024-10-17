<?php
require_once('../data/config.inc.php');
require_once('../data/DatabaseHelper.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/../data/f1.db']);

header('Content-Type: application/json');

$query = "SELECT races.name, drivers.forename, drivers.surname, constructors.name AS constructor, results.position, results.points 
          FROM results
          JOIN drivers ON results.driverId = drivers.driverId
          JOIN constructors ON results.constructorId = constructors.constructorId
          JOIN races ON results.raceId = races.raceId";

if (isset($_GET['ref'])) {
    $raceId = intval($_GET['ref']);
    $query .= " WHERE results.raceId = $raceId";
} elseif (isset($_GET['driver'])) {
    $driverRef = $_GET['driver'];
    $query .= " WHERE drivers.driverRef = '$driverRef'";
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
