<?php 
    include './db.php';
    $sql = "SELECT * FROM customer";
    $query = $connection->query($sql);

    echo "$query->num_rows";

?>