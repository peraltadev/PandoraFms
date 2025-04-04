<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Form</title>
</head>
<body>

<h1>Reserva una cita</h1>
<?php if (isset($_GET['success'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;margin-bottom: 20px; width: 100px;">
        Cita creada correctamente!
    </div>
<?php elseif (isset($_GET['error'])): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; width: 100px;">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>
<form id="appointment-form" method="POST" action="submit.php">
    <label for="name">Nombre:</label>
    <input type="text" name="name" id="name" required><br><br>

    <label for="dni">DNI:</label>
    <input type="text" name="dni" id="dni" required><br><br>
    <div id="dni-error" style="color: red; margin-top: 5px;"></div>

    <label for="phone">Telefono:</label>
    <input type="text" name="phone" id="phone" required><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="type">Tipo de cita:</label>
    <select name="type" id="type">
        <option value="first">Primera cita</option>
    </select><br><br>

    <button type="submit">Reservar</button>
</form>

<script>
document.getElementById('dni').addEventListener('blur', function() {
    const dni = this.value;
    const dniError = document.getElementById('dni-error');
    if(validarDni(dni)){
        fetch('check-dni.php?dni=' + encodeURIComponent(dni))
        .then(response => response.json())
        .then(data => {
            const typeSelect = document.getElementById('type');
            typeSelect.innerHTML = ''; // Limpiar las opciones

            if (data.exists) {
                // Si el paciente existe, puede hacer revisión
                typeSelect.innerHTML = `
                    <option value="first">Primera cita</option>
                    <option value="review">Revisión</option>
                `;
            } else {
                // Si no existe, solo primera cita
                typeSelect.innerHTML = `
                    <option value="first">Primera cita</option>
                `;
            }
        });
    }
    else{
        dniError.textContent = 'El dni no es correcto.';
        dniError.style.color = 'red';
        dniError.style.fontSize = '16px';
        dniError.style.marginBottom = '5px';

    }
   
});

function validarDni(dni){
    // Expresión regular para validar el DNI español (8 dígitos + letra)
    const letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
    const number = parseInt(dni.slice(0, 8), 10);
    const expectedLetter = letters[number % 23];

    if (dni.charAt(8) !== expectedLetter) {
       return false
    }
    return true
}

</script>

</body>
</html>
