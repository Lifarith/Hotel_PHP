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

// Incluir encabezado y barra lateral
include_once "header.php";
include_once "sidebar.php";

// Consulta con JOIN para traer tipo, precio y capacidad
$query = "
SELECT 
    r.room_id,
    r.room_no,
    r.status,
    rt.room_type,
    rt.price,
    rt.max_person
FROM room r
LEFT JOIN room_type rt ON r.room_type_id = rt.room_type_id
";

$result = mysqli_query($connection, $query);
if (!$result) {
    die("Error en la consulta: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Estado de Habitaciones</title>
<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
    }

    /* Contenedor principal que evita que el sidebar tape el contenido */
    .main-content {
        margin-left: 250px; /* Ajusta según el ancho de tu sidebar */
        padding: 30px;
        text-align: center;
    }

    h2 {
        margin-bottom: 25px;
        color: #333;
    }

    .rooms-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .room-card {
        width: 160px;
        height: 130px;
        padding: 15px;
        border-radius: 15px;
        cursor: pointer;
        background-color: white;
        color: #000;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .room-card:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 12px rgba(0,0,0,0.3);
    }

    /* Colores de fondo completos según estado */
    .available {
        background-color: #28a745; /* Verde */
        color: white;
    }

    .occupied {
        background-color: #dc3545; /* Rojo */
        color: white;
    }

    .room-card h3 {
        margin: 5px 0;
        font-size: 22px;
    }

    .room-card p {
        margin: 2px 0;
        font-size: 16px;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 10;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 320px;
        color: #3333;
        text-align: left;
    }

    .close {
        float: right;
        font-size: 22px;
        cursor: pointer;
    }

    .close:hover {
        color: #d00;
    }
</style>
</head>
<body>

<div class="main-content">
    <h2>Estado de las Habitaciones</h2>

    <div class="rooms-container">
    <?php while ($row = mysqli_fetch_assoc($result)) : 
        $colorClass = ($row['status'] == 1) ? 'occupied' : 'available';
    ?>
        <div class="room-card <?= $colorClass ?>" onclick="openModal(<?= htmlspecialchars(json_encode($row)) ?>)">
            <h3><?= htmlspecialchars($row['room_no']) ?></h3>
            <p><?= htmlspecialchars($row['room_type']) ?></p>
            <p><strong><?= ($row['status'] == 1) ? 'Ocupada' : 'Disponible' ?></strong></p>
        </div>
    <?php endwhile; ?>
    </div>
</div>

<!-- Modal -->
<div id="roomModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalRoomNo"></h3>
        <p><strong>Tipo:</strong> <span id="modalRoomType"></span></p>
        <p><strong>Precio:</strong> <span id="modalRoomPrice"></span></p>
        <p><strong>Capacidad:</strong> <span id="modalRoomCapacity"></span> personas</p>
        <p><strong>Estado:</strong> <span id="modalRoomStatus"></span></p>
    </div>
</div>

<script>
function openModal(data) {
    document.getElementById("modalRoomNo").innerText = "Habitación " + data.room_no;
    document.getElementById("modalRoomType").innerText = data.room_type;
    document.getElementById("modalRoomPrice").innerText = new Intl.NumberFormat('es-CO').format(data.price) + " COP";
    document.getElementById("modalRoomCapacity").innerText = data.max_person;
    document.getElementById("modalRoomStatus").innerText = data.status == 1 ? "Ocupada" : "Disponible";

    document.getElementById("roomModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("roomModal").style.display = "none";
}
</script>

</body>
</html>
