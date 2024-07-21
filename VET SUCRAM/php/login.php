<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vet_sucram";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT contrasena FROM usuarios WHERE telefono = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $telefono);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($hashed_password && password_verify($contrasena, $hashed_password)) {
        header("Location: nosotros.php");
        exit();
    } else {
        $login_error = "Número de teléfono o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h1>Iniciar Sesión</h1>
            <form method="post" action="login.php">
                <label for="telefono">Número de Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <button type="submit">Ingresar</button>

                <?php if ($login_error): ?>
                    <p class="error"><?php echo $login_error; ?></p>
                <?php endif; ?>
                </form>
        </body>
        </html>