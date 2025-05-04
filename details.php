<?php
$conn = new mysqli("localhost", "root", "", "helphub");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Projet non trouvé.";
    exit();
}

$id_projet = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM projet WHERE id_projet = ?");
$stmt->bind_param("i", $id_projet);
$stmt->execute();
$projet = $stmt->get_result()->fetch_assoc();

$sql = "SELECT d.nom, d.prenom, dp.montant_participation 
        FROM donateur_projet dp 
        JOIN donateur d ON dp.id_donateur = d.id_donateur 
        WHERE dp.id_projet = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_projet);
$stmt->execute();
$donations = $stmt->get_result();

$total_collecte = $projet['montant_total_collecte'];
$total_cible = $projet['montant_total_a_collecter'];
$reste = $total_cible - $total_collecte;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détails du projet</title>
  <link rel="stylesheet" href="details.css">
</head>
<body>
<div class="container">
  <h1><?php echo htmlspecialchars($projet['titre']); ?> : <?php echo $total_cible; ?> TND</h1>

  <table>
    <thead>
      <tr>
        <th>Donateur</th>
        <th>Montant</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $donations->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?></td>
          <td><?php echo $row['montant_participation']; ?> TND</td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="totals">
    <div class="box">Montant total collecté: <?php echo $total_collecte; ?> TND</div>
    <div class="box">Reste de montant: <?php echo $reste; ?> TND</div>
  </div>

  <div class="actions">
    <a href="association_dashboard.php" class="back-btn">← Retour</a>
    <form action="delete.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
      <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">
      <button type="submit" class="delete-btn">Supprimer</button>
    </form>
  </div>
</div>
</body>
</html>
