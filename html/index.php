<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Form</title>
</head>
<body>

<h1>Book an Appointment</h1>

<form id="appointment-form" method="POST" action="submit.php">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required><br><br>

    <label for="dni">DNI:</label>
    <input type="text" name="dni" id="dni" required><br><br>

    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" required><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="type">Type of Appointment:</label>
    <select name="type" id="type">
        <option value="first">First Appointment</option>
    </select><br><br>

    <button type="submit">Submit</button>
</form>

<script>
document.getElementById('dni').addEventListener('blur', function() {
    const dni = this.value;

    fetch('check-dni.php?dni=' + encodeURIComponent(dni))
        .then(response => response.json())
        .then(data => {
            const typeSelect = document.getElementById('type');
            typeSelect.innerHTML = ''; // Limpiar las opciones

            if (data.exists) {
                // Si el paciente existe, puede hacer revisión
                typeSelect.innerHTML = `
                    <option value="first">First Appointment</option>
                    <option value="review">Review</option>
                `;
            } else {
                // Si no existe, solo primera cita
                typeSelect.innerHTML = `
                    <option value="first">First Appointment</option>
                `;
            }
        });
});
</script>

</body>
</html>
