<?php
require_once('../data/config.inc.php');
require_once('../data/DatabaseHelper.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/../data/f1.db']);

header('Content-Type: application/json');

$query = "SELECT raceId, driverId, q1, q2, q3 FROM qualifying";

if (isset($_GET['ref'])) {
    $raceId = $_GET['ref'];
    $query .= " WHERE raceId = $raceId";
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$qualifying = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($qualifying);
