<?php
require_once('../data/config.inc.php');
require_once('../data/DatabaseHelper.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/../data/f1.db']);

$year = isset($_GET['year']) ? $_GET['year'] : 2022;

$sql = "SELECT raceId, name, round, year, date, time, url FROM races WHERE year = $year";

if (!empty($_GET['raceId'])) {
    $raceId = $_GET['raceId'];
    $sql .= " AND raceId = $raceId";
}

$sql .= " ORDER BY round";

$query = $pdo->prepare($sql);
$query->execute();

$results = $query->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($results);
