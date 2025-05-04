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
$id_donateur = $_SESSION['id_donateur'] ?? null;
if (!$id_donateur) {
    die("Erreur : Donateur non connecté.");
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$stmt = $pdo->prepare("
    SELECT p.*, SUM(dp.montant_participation) AS total_donations
    FROM projet p
    JOIN donateur_projet dp ON p.id_projet = dp.id_projet
    WHERE dp.id_donateur = ? 
    AND p.titre LIKE ?
    GROUP BY p.id_projet
");

$stmt->execute([$id_donateur, "%$search%"]);
$projects = $stmt->fetchAll();
$totalFunded = 0;
foreach ($projects as $project) {
    $totalFunded += $project['total_donations'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Projets Financé</title>
    <link rel="stylesheet" href="styledonateur1.css">
</head>
<body>

<header>
    <li id="logo"><a href="index.html"><img src="téléchargement__1_-removebg-preview.png" alt="Logo"></a></li>
</header>

<div class="container">

    <form class="search-bar" method="GET">
        <input type="text" name="search" placeholder="Rechercher un projet financé" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">🔍</button>
    </form>

    <h2>✅ Projets Déjà Financés</h2>

    <?php if (count($projects) > 0): ?>
        <?php foreach ($projects as $project): ?>
            <div class="project-card">
                <h3><?= htmlspecialchars($project['titre']) ?></h3>
                <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                <p><strong>Montant donnee:</strong> <?= $project['total_donations'] ?> dt</p> 
                <p><strong>Objectif:</strong> <?= $project['montant_total_a_collecter'] ?> dt</p>
            </div>
        <?php endforeach; ?>

        <div class="project-card total-funded" style="background-color:#f0f8ff; margin-top:20px; padding: 15px; border: 2px solid #4CAF50;">
            <h3 style="color:#2e7d32;">💰 Total collecté pour tous les projets financés: <?= $totalFunded ?> dt</h3>
        </div>
    <?php else: ?>
        <p>Aucun projet financé trouvé pour "<?= htmlspecialchars($search) ?>".</p>
    <?php endif; ?>

    <br>
    <a href="pagedonateur.php" class="retour-btn" style="display:inline-block; padding:10px 20px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px; margin-top:20px;">⬅ Retour à la page précédente</a>

</div>

</body>
</html>
