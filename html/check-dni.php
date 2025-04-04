<?php
require 'db.php';

header('Content-Type: application/json');

$dni = $_GET['dni'] ?? '';

if (empty($dni)) {
    echo json_encode(['exists' => false]);
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM patient WHERE dni = ?');
$stmt->execute([$dni]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['exists' => $patient !== false]);
