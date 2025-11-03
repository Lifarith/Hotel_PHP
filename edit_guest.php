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

// Verificar si se envió el ID por GET
if (!isset($_GET['id'])) {
    header("Location: guest.php");
    exit();
}

$customer_id = $_GET['id'];

// Obtener datos actuales del huésped
$query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Huésped no encontrado.");
}

$guest = mysqli_fetch_assoc($result);

// Si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($connection, $_POST['customer_name']);
    $contact = mysqli_real_escape_string($connection, $_POST['contact_no']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    $updateQuery = "UPDATE customer 
                    SET customer_name = '$name', 
                        contact_no = '$contact', 
                        email = '$email' 
                    WHERE customer_id = '$customer_id'";

    if (mysqli_query($connection, $updateQuery)) {
        echo "<script>alert('Huésped actualizado correctamente'); window.location='guest.php';</script>";
    } else {
        echo "Error al actualizar: " . mysqli_error($connection);
    }
}

include_once "header.php";
include_once "sidebar.php";
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
        background-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
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
        <h2>Editar Huésped</h2>

        <form method="POST" action="">
            <label for="customer_name">Nombre del huésped</label>
            <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($guest['customer_name']); ?>" required>

            <label for="contact_no">Contacto</label>
            <input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($guest['contact_no']); ?>" required>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($guest['email']); ?>" required>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
                <a href="guest.php" class="btn btn-secondary">↩ Volver</a>
            </div>
        </form>
    </div>
</div>

<?php include_once "footer.php"; ?>
