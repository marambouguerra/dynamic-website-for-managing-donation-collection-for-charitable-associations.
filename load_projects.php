<?php
$host = 'localhost';
$db = 'helphub';
$user = 'root';
$pass = '';

if (!isset($_SESSION['id_responsable'])) {
    echo "<p>Vous devez être connecté pour voir vos projets.</p>";
    exit;
}

$id_responsable = $_SESSION['id_responsable'];

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id_projet, titre, description FROM projet WHERE id_responsable_association = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_responsable);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="project-card">';
        echo '<div class="project-info">';
        echo '<h3>' . htmlspecialchars($row["titre"]) . '</h3>';
        echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
        echo '</div>';
        echo '<div class="project-actions">';
        echo '<a href="details.php?id=' . $row["id_projet"] . '" class="btn details-btn">details</a><br>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="project-card"><p>Aucun projet trouvé.</p></div>';
}

$stmt->close();
$conn->close();
?>
