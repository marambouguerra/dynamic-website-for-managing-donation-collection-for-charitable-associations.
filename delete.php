<?php
$conn = new mysqli("localhost", "root", "", "helphub");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id_projet'])) {
    $id = intval($_POST['id_projet']);
    $conn->query("DELETE FROM donateur_projet WHERE id_projet = $id");
    $conn->query("DELETE FROM projet WHERE id_projet = $id");

    header("Location: association_dashboard.php");
    exit();
} else {
    echo "ID de projet non fourni.";
}
