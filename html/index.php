<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserva una cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        #dni-error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Reserva una cita</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="message success">
            ¡Cita creada correctamente!
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="message error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form id="appointment-form" method="POST" action="submit.php">
        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" required>

        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required>
        <div id="dni-error"></div>

        <label for="phone">Teléfono:</label>
        <input type="text" name="phone" id="phone" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="type">Tipo de cita:</label>
        <select name="type" id="type">
            <option value="first">Primera cita</option>
        </select>

        <button type="submit">Reservar</button>
    </form>
</div>

<script>
document.getElementById('dni').addEventListener('blur', function() {
    const dni = this.value.toUpperCase();
    const dniError = document.getElementById('dni-error');
    dniError.textContent = '';

    if(validarDni(dni)){
        fetch('check-dni.php?dni=' + encodeURIComponent(dni))
        .then(response => response.json())
        .then(data => {
            const typeSelect = document.getElementById('type');
            typeSelect.innerHTML = '';

            if (data.exists) {
                typeSelect.innerHTML = `
                    <option value="first">Primera cita</option>
                    <option value="review">Revisión</option>
                `;
            } else {
                typeSelect.innerHTML = `
                    <option value="first">Primera cita</option>
                `;
            }
        });
    } else {
        dniError.textContent = 'El DNI no es correcto.';
    }
});

function validarDni(dni){
    const dniPattern = /^[0-9]{8}[A-Z]$/;
    if (!dniPattern.test(dni)) {
        return false;
    }

    const letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
    const number = parseInt(dni.slice(0, 8), 10);
    const expectedLetter = letters[number % 23];

    return dni.charAt(8) === expectedLetter;
}
</script>

</body>
</html>
