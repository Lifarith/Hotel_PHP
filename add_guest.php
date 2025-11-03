<?php
include_once "db.php";
session_start();

// Verificar sesión activa
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $userQuery = "SELECT * FROM user WHERE id = '$user_id'";
    $result = mysqli_query($connection, $userQuery);
    $user = mysqli_fetch_assoc($result);
} else {
    header('Location: login.php');
    exit();
}

include_once "header.php";
include_once "sidebar.php";

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($connection, $_POST['customer_name']);
    $contact = mysqli_real_escape_string($connection, $_POST['contact_no']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // Validar campos vacíos
    if (empty($name) || empty($contact) || empty($email)) {
        echo "<script>alert('Por favor completa todos los campos.');</script>";
    } else {
        // Insertar nuevo huésped
        $query = "INSERT INTO customer (customer_name, contact_no, email) 
                  VALUES ('$name', '$contact', '$email')";

        if (mysqli_query($connection, $query)) {
            echo "<script>alert('Huésped agregado correctamente'); window.location='guest.php';</script>";
        } else {
            echo "Error al agregar huésped: " . mysqli_error($connection);
        }
    }
}
?>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f5f6fa;
    }

    .main-content {
        margin-left: 240px;
        padding: 30px;
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    form label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #333;
    }

    form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        border: none;
        font-size: 15px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #28a745;
    }

    .btn-primary:hover {
        background-color: #218838;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-group {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
</style>

<div class="main-content">
    <div class="form-container">
        <h2>Agregar Nuevo Huésped</h2>

        <form method="POST" action="">
            <label for="customer_name">Nombre del huésped</label>
            <input type="text" id="customer_name" name="customer_name" placeholder="Ej: Juan Pérez" required>

            <label for="contact_no">Número de contacto</label>
            <input type="text" id="contact_no" name="contact_no" placeholder="Ej: 3104567890" required>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" placeholder="Ej: juanperez@mail.com" required>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Guardar Huésped</button>
                <a href="guest.php" class="btn btn-secondary">↩ Volver</a>
            </div>
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>
