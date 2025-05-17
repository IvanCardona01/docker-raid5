<?php
// ---------- CONEXIÓN MYSQL ----------
$host = 'mysql';
$user = 'user';
$pass = 'userpass';
$db = 'app_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Error de conexión MySQL: " . $conn->connect_error);
}

$mensaje = "";

// ---------- CONEXIÓN MONGODB ----------
require 'vendor/autoload.php'; // Asegúrate de tener MongoDB PHP library
try {
    $mongoClient = new MongoDB\Client("mongodb://mongo_db:27017");
    $mongoDB = $mongoClient->selectDatabase("respaldo");
    $mongoCollection = $mongoDB->clientes;
} catch (Exception $e) {
    die("❌ Error de conexión MongoDB: " . $e->getMessage());
}

// ---------- PROCESAR FORMULARIO ----------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Insertar en MySQL
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $email);
    $mysql_result = $stmt->execute();

    // Insertar en MongoDB
    $mongo_result = $mongoCollection->insertOne([
        'nombre' => $nombre,
        'email' => $email,
        'fecha' => new MongoDB\BSON\UTCDateTime()
    ]);

    if ($mysql_result && $mongo_result->getInsertedCount() === 1) {
    //if ($mysql_result) {
        $mensaje = "✅ Datos insertados correctamente.";
    } else {
        $mensaje = "❌ Error al insertar los datos.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background: #45a049;
        }
        .mensaje {
            background-color: #e0ffe0;
            padding: 10px;
            border-left: 5px solid #4CAF50;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.95em;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registrar Usuario</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="submit" value="Guardar">
        </form>
    </div>

    <script>
        setTimeout(() => {
            const msg = document.getElementById('mensaje');
            if (msg) msg.remove();
        }, 3000);
    </script>
</body>
</html>
