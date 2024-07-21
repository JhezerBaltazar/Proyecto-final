<?php
include '_header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vet_sucram";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = '';
$question = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question'])) {
    $question = $_POST['question'];

    $stmt = $conn->prepare("SELECT respuesta FROM preguntas WHERE pregunta = ?");
    $stmt->bind_param("s", $question);
    $stmt->execute();
    $stmt->bind_result($response);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['question'])) {
    $nombres = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $direccion = $_POST['direccion'];
    $referencia = $_POST['referencia'];
    $distrito = $_POST['distrito'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $nombre_mascota = $_POST['mascota'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Validar que todos los campos requeridos estén presentes
    if (empty($nombres) || empty($apellidos) || empty($direccion) || empty($distrito) || empty($telefono) || empty($email) || empty($contrasena)) {
        echo "<script>alert('Todos los campos son obligatorios.')</script>";
    } else {
        $sql = "INSERT INTO usuarios (nombres, apellidos, direccion, referencia, distrito, telefono, email, nombre_mascota, contrasena)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $nombres, $apellidos, $direccion, $referencia, $distrito, $telefono, $email, $nombre_mascota, $contrasena);

        if ($stmt->execute()) {
            echo "<script>alert('Registro exitoso.')</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<main>
    <link rel="stylesheet" href="../css/informacion.css">

    <body>
        <div class="chat-container">
            <h2>Chat Bot</h2>
            <form method="post">
                <button type="submit" name="question" value="¿Cuáles son los horarios de atención?">¿Cuáles son los horarios de atención?</button>
                <button type="submit" name="question" value="Consulta Adicional">Consulta adicional - WhatsApp</button>
                <button type="submit" name="question" value="¿Cuánto es el costo de la consulta a domicilio?">¿Cuánto es el costo de la consulta a domicilio?</button>
                <button type="submit" name="question" value="¿Qué servicios ofrecen?">¿Qué servicios ofrecen?</button>
                <button type="submit" name="question" value="¿Dónde están ubicados?">¿Dónde están ubicados?</button>
                <button type="submit" name="question" value="¿Ofrecen servicios de emergencia?">¿Ofrecen servicios de emergencia?</button>
                <button type="submit" name="question" value="¿Qué métodos de pago aceptan?">¿Qué métodos de pago aceptan?</button>
            </form>

            <?php if ($response): ?>
                <div class="response">
                    <p><?php echo nl2br($response); ?></p>
                    <?php if ($question == 'Consulta Adicional'): ?>
                        <a href="https://wa.me/51956783355" target="_blank">Contactar por WhatsApp</a>
                    <?php else: ?>
                        <form method="post">
                            <button type="submit" name="question" value="">Hacer otra pregunta</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-container">
            <h1>Regístrate</h1>
            <h3>Para recibir promociones y novedades y artículos de interés</h3>

            <form method="post">
                <label for="nombre">Nombres:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>

                <label for="referencia">Referencia:</label>
                <input type="text" id="referencia" name="referencia" required>

                <label for="distrito">Distrito:</label>
                <input type="text" id="distrito" name="distrito" placeholder="Distrito" required>

                <label for="telefono">Número de teléfono:</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Número de Teléfono" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" placeholder="Correo electrónico" required>

                <label for="mascota">Nombre de la Mascota:</label>
                <input type="text" id="mascota" name="mascota" placeholder="Nombre de la Mascota">

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <button type="submit">Registrarse</button>
            </form>
        </div>
    </body>
</main>

<?php
include 'footer.php';
?>
