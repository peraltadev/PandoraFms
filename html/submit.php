<?php
require 'db.php';

$name = $_POST['name'] ?? '';
$dni = $_POST['dni'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';

// Validación básica
if (empty($name) || empty($dni) || empty($phone) || empty($email)) {
    die('All fields are required.');
}

// Verificar si el paciente ya existe
$stmt = $pdo->prepare('SELECT id FROM patient WHERE dni = ?');
$stmt->execute([$dni]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if ($patient) {
    $patientId = $patient['id'];
} else {
    // Insertar el nuevo paciente
    $stmt = $pdo->prepare('INSERT INTO patient (name, dni, phone, email) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $dni, $phone, $email]);
    $patientId = $pdo->lastInsertId();
}

// Obtener la última cita registrada
$stmt = $pdo->query('SELECT date FROM appointment ORDER BY date DESC LIMIT 1');
$lastAppointment = $stmt->fetch(PDO::FETCH_ASSOC);

if ($lastAppointment) {
    $lastDateTime = new DateTime($lastAppointment['date']);
} else {
    // Si no hay citas, empezamos hoy a las 10:00
    $lastDateTime = new DateTime();
    $lastDateTime->setTime(10, 0);
}

// Calcular la siguiente hora
// Añade 1 hora a la última cita
$nextDateTime = clone $lastDateTime;
$nextDateTime->modify('+1 hour');

// Si la hora es después de las 22:00, pasa al día siguiente a las 10:00
if ((int)$nextDateTime->format('H') >= 22) {
    $nextDateTime->modify('+1 day')->setTime(10, 0);
}

// Guardar la cita
$stmt = $pdo->prepare('INSERT INTO appointment (patient_id, date) VALUES (?, ?)');
$stmt->execute([$patientId, $nextDateTime->format('Y-m-d H:i:s')]);

echo "Appointment successfully created for: " . $nextDateTime->format('Y-m-d H:i:s');
