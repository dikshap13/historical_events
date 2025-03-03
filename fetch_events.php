<?php
require('database.php');

$query = "SELECT id, title, description, event_date, latitude, longitude FROM events ORDER BY event_date ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($events);
