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
if(!validateDNI($dni)) {
    header('Location: index.php?error=Dni%20incorrecto');
    exit;
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
try {
    $pdo->beginTransaction();
    // Seleccionamos la última cita y bloqueamos la fila hasta que terminemos la transacción
    $stmt = $pdo->query('SELECT date FROM appointment ORDER BY date DESC LIMIT 1 FOR UPDATE');
    $lastAppointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($lastAppointment) {
        $lastDateTime = new DateTime($lastAppointment['date']);
    } else {
        // Si no hay citas, empezamos hoy a las 10:00
        $lastDateTime = new DateTime();
        $lastDateTime->setTime(9, 0);
    }

    // Calcular la siguiente hora
    $nextDateTime = clone $lastDateTime;
    $nextDateTime->modify('+1 hour');

    if ((int)$nextDateTime->format('H') >= 22) {
        $nextDateTime->modify('+1 day')->setTime(10, 0);
    }

    // Guardar la cita
    $stmt = $pdo->prepare('INSERT INTO appointment (patient_id, date) VALUES (?, ?)');
    $stmt->execute([$patientId, $nextDateTime->format('Y-m-d H:i:s')]);

    $pdo->commit();

    header('Location: index.php?success=1');
    exit;


} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    die("Error: " . $e->getMessage());
}


function validateDNI($dni) {
    $dni = strtoupper($dni);
    if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
        return false;
    }

    $number = intval(substr($dni, 0, 8));
    $letter = substr($dni, -1);
    $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';

    return $letter === $letters[$number % 23];
}

