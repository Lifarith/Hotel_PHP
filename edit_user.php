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

// Obtener el ID del usuario a editar
if (!isset($_GET['id'])) {
    header('Location: user.php');
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM user WHERE id = $id";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Usuario no encontrado'); window.location='user.php';</script>";
    exit();
}

$userData = mysqli_fetch_assoc($result);

// Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'];

    // Si el campo de contraseña se deja vacío, no se actualiza
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $update = "UPDATE user SET name='$name', username='$username', email='$email', password='$hashedPassword' WHERE id=$id";
    } else {
        $update = "UPDATE user SET name='$name', username='$username', email='$email' WHERE id=$id";
    }

    if (mysqli_query($connection, $update)) {
        echo "<script>alert('Usuario actualizado correctamente'); window.location='user.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el usuario: " . mysqli_error($connection) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            width: 60%;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .note {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="form-container">
            <h2>Editar Usuario</h2>
            <form method="POST" action="">
                <label>Nombre completo:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required>

                <label>Nombre de usuario:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($userData['username']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>

                <label>Nueva contraseña (opcional):</label>
                <input type="password" name="password">
                <span class="note">Si no deseas cambiar la contraseña, deja este campo vacío.</span>

                <button type="submit">Actualizar Usuario</button>
            </form>
            <a class="back-link" href="user.php">← Volver a la lista de usuarios</a>
        </div>
    </div>
</body>
</html>

<?php include_once "footer.php"; ?>
