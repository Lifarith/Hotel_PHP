<?php
include_once "db.php";
session_start();

// Verificar si hay sesión activa
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $userQuery = "SELECT * FROM user WHERE id = '$user_id'";
    $result = mysqli_query($connection, $userQuery);
    $user = mysqli_fetch_assoc($result);
} else {
    header('Location: login.php');
    exit();
}

// Incluir encabezado y barra lateral
include_once "header.php";
include_once "sidebar.php";

// Consultar todos los usuarios
$query = "SELECT id, name, username, email FROM user";
$result = $connection->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .main-content {
            margin-left: 250px; /* Ajusta según el ancho de tu sidebar */
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #28a745;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-add {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="main-content">
    <h2>Lista de Usuarios</h2>

    <div style="text-align:right; width:90%; margin:auto;">
        <a href="add_user.php" class="btn-add">+ Agregar Usuario</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <a class="btn btn-edit" href="edit_user.php?id=<?= $row['id'] ?>">Editar</a>
                        <a class="btn btn-delete" href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No hay usuarios registrados.</td></tr>
        <?php endif; ?>
    </table>
</div>
<div class="row">
        <div class="col-sm-12">
        <p class="back-link">Desarrollado por Lifarith Ortega M.</p>
        </div>
    </div>

<?php include_once "footer.php"; ?>

</body>
</html>
