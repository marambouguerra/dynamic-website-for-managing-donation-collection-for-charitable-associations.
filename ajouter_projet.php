<?php
session_start();
if (!isset($_SESSION['id_responsable'])) {
    header("Location: index.html?login=required");
    exit();
}
$conn = new mysqli("localhost", "root", "", "helphub");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $montant = floatval($_POST["montant"]);
    $date_limite = $_POST["date_limite"];
    $id_responsable = $_SESSION['id_responsable'];

    if ($titre && $description && $montant > 0 && $date_limite) {
        $sql = "INSERT INTO projet (titre, description, montant_total_a_collecter, date_limite, id_responsable_association)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $titre, $description, $montant, $date_limite, $id_responsable);
        $stmt->execute();
        $stmt->close();

        header("Location: association_dashboard.php?add=success");
        exit();
    } else {
        echo "<script>alert('Veuillez remplir tous les champs correctement.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter Projet</title>
  <link rel="stylesheet" href="ajouter_projet.css">
</head>
<body>
  

  <div class="form-container">
    <form method="post" action="">
      <h2>Ajouter un projet</h2>
      <label for="titre">Titre</label>
      <input type="text" id="titre" name="titre" required>

      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" required></textarea>

      <label for="montant">Montant à collecter</label>
      <input type="number" id="montant" name="montant" step="0.01" required>

      <label for="date_limite">Date limite</label>
      <input type="date" id="date_limite" name="date_limite" required>

      <button type="submit">Ajouter</button>
      <a href="association_dashboard.php" class="retour">← Retour</a>
    </form>
  </div>
  <script src="ajoutpro.js"></script>
</body>
</html>    
