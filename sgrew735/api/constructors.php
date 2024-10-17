<?php
require_once('../data/config.inc.php');
require_once('../data/DatabaseHelper.php');

$pdo = DatabaseHelper::createConnection(['sqlite:' . __DIR__ . '/../data/f1.db']);

header('Content-Type: application/json');

if (isset($_GET['ref'])) {
    $constructorRef = $_GET['ref'];

    $query = "SELECT constructorRef, name, nationality, url FROM constructors WHERE constructorRef = '$constructorRef'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Constructor not found']);
    }
} else {
    $query = "SELECT constructorRef, name, nationality, url FROM constructors";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
}
