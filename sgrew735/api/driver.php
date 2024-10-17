<?php
require_once('../data/config.inc.php');
require_once('../data/DatabaseHelper.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/../data/f1.db']);

header('Content-Type: application/json');

$query = "SELECT driverId, forename, surname, dob, nationality FROM drivers";

if (isset($_GET['ref'])) {
    $driverRef = $_GET['ref'];

    $query .= " WHERE driverRef = '$driverRef'";
    $stmt = $pdo->prepare($query);
} elseif (isset($_GET['race'])) {
    $raceId = $_GET['race'];

    $query = "SELECT races.name, results.position, results.points, drivers.forename, drivers.surname 
              FROM results
              JOIN drivers ON results.driverId = drivers.driverId
              JOIN races ON results.raceId = races.raceId
              WHERE races.raceId = $raceId";
    $stmt = $pdo->prepare($query);
} else {
    $stmt = $pdo->prepare($query);
}

$stmt->execute();
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($drivers);
