<?php
session_start();
$host = 'localhost';
$db = 'helphub';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_projet'], $_POST['montant'])) {
    $id_projet = intval($_POST['id_projet']);
    $montant = floatval($_POST['montant']);

    $id_donateur = $_SESSION['id_donateur'] ?? null;

    if ($id_donateur === null) {
        die("Erreur: Donateur non connecté.");
    }
    $stmt = $pdo->prepare("SELECT montant_total_a_collecter, montant_total_collecte FROM projet WHERE id_projet = ?");
    $stmt->execute([$id_projet]);
    $projectData = $stmt->fetch();

    if (!$projectData) {
        die("Erreur: Projet introuvable.");
    }

    $reste = $projectData['montant_total_a_collecter'] - $projectData['montant_total_collecte'];

    if ($montant > $reste) {
        echo "<script>alert('Erreur : Le montant que vous avez entré dépasse le montant restant à collecter (" . $reste . " dt).'); window.location.href='detailspourdonateur.php?id=$id_projet';</script>";
exit;
    }
    $stmt = $pdo->prepare("UPDATE projet SET montant_total_collecte = montant_total_collecte + ? WHERE id_projet = ?");
    $stmt->execute([$montant, $id_projet]);
    $stmt = $pdo->prepare("INSERT INTO donateur_projet (id_projet, id_donateur, montant_participation) VALUES (?, ?, ?)");
    $stmt->execute([$id_projet, $id_donateur, $montant]);
    header("Location: detailspourdonateur.php?id=$id_projet");
    exit;
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM projet WHERE id_projet = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    echo "Projet introuvable.";
    exit;
}

$reste = $project['montant_total_a_collecter'] - $project['montant_total_collecte'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Projet</title>
    <link rel="stylesheet" href="styleparticiper.css">
</head>
<body>

<header>
    <img src="téléchargement__1_-removebg-preview.png" alt="Logo" class="logo">
</header>

<main class="details-container">
    <h2><?= htmlspecialchars($project['titre']) ?> : <?= $project['montant_total_a_collecter'] ?> dt</h2>

    <div class="description-box">
        <strong>Description:</strong><br>
        <?= nl2br(htmlspecialchars($project['description'])) ?>
    </div>

    <p><strong>Montant restant à collecter:</strong> <?= $reste ?> dt</p>

    <form method="post" action="detailspourdonateur.php?id=<?= $project['id_projet'] ?>">
        <input type="hidden" name="id_projet" value="<?= $project['id_projet'] ?>">
        <input type="number" name="montant" placeholder="Montant à donner" min="1" required>
        <button type="submit">Participer</button>
    </form>

    <br><br>
    <a href="pagedonateur.php" class="retour-btn">⬅ Retour à la page précédente</a>
</main>

</body>
</html>
