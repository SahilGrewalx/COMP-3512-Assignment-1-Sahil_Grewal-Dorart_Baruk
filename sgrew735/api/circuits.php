<?php
require_once('../data/config.inc.php');
require_once('../data/DatabaseHelper.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/../data/f1.db']);

header('Content-Type: application/json');

$query = "SELECT circuitId, name, location, country FROM circuits";
if (isset($_GET['ref'])) {
    $circuitRef = $_GET['ref'];
    $query .= " WHERE circuitRef = '$circuitRef'";
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$circuits = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($circuits);
