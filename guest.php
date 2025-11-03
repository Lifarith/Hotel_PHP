<?php
include_once "db.php";
session_start();

// Verificar sesión
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

// Consulta de huéspedes
$query = "SELECT customer_id, customer_name, contact_no, email FROM customer";
$result_customers = mysqli_query($connection, $query);
?>

<style>
    /* Estilo general */
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f5f6fa;
        margin: 0;
        padding: 0;
    }

    .main-content {
        margin-left: 240px; /* espacio para el sidebar */
        padding: 30px;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
        text-transform: uppercase;
        font-size: 14px;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .btn {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        transition: background 0.3s;
    }

    .btn-primary {
        background-color: #28a745;
    }

    .btn-primary:hover {
        background-color: #218838;
    }

    .btn-edit {
        background-color: #ffc107;
    }

    .btn-edit:hover {
        background-color: #e0a800;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

</style>

<div class="main-content">
    <div class="container">
        <div class="top-bar">
            <h2>Listado de Huéspedes</h2>
            <a href="add_guest.php" class="btn btn-primary">➕ Agregar Huésped</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_customers) > 0) {
                    while ($row = mysqli_fetch_assoc($result_customers)) {
                        echo "<tr>
                                <td>{$row['customer_id']}</td>
                                <td>{$row['customer_name']}</td>
                                <td>{$row['contact_no']}</td>
                                <td>{$row['email']}</td>
                                <td class='actions'>
                                    <a href='edit_guest.php?id={$row['customer_id']}' class='btn btn-edit'>Editar</a>
                                    <a href='delete_guest.php?id={$row['customer_id']}' class='btn btn-delete' onclick='return confirm(\"¿Seguro que deseas eliminar este huésped?\")'>Eliminar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay huéspedes registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once "footer.php"; ?>
