<?php
header('Content-Type: application/json');

// Verifica si se recibieron los datos
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// Validación básica
if (empty($nombre) || empty($correo) || empty($mensaje)) {
    echo json_encode(["error" => "Todos los campos son obligatorios."]);
    exit;
}

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "seminario_db");

if ($mysqli->connect_error) {
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}

// Preparar y ejecutar la consulta
$stmt = $mysqli->prepare("INSERT INTO mensajes_contacto (nombre, correo, mensaje) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $correo, $mensaje);

if ($stmt->execute()) {
    echo json_encode(["data" => ["nombre" => htmlspecialchars($nombre)]]);
} else {
    echo json_encode(["error" => "Error al guardar el mensaje."]);
}

$stmt->close();
$mysqli->close();
?>
