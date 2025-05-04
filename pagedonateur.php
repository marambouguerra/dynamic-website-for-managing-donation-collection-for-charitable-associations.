<?php
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
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$stmt = $pdo->prepare("
    SELECT * 
    FROM projet 
    WHERE description LIKE ?  -- searching in description now
    AND date_limite >= CURDATE() 
    AND montant_total_collecte < montant_total_a_collecter
");
$stmt->execute(["%$search%"]);
$projects = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des projets</title>
    <link rel="stylesheet" href="styledonateur1.css">
</head>
<body>
<header>
<li id="logo"><a href="index.html"><img src="téléchargement__1_-removebg-preview.png"></a></li>
</header>
<div class="container">
    <form class="search-bar" method="GET">
        <input type="text" name="search" placeholder="Rechercher dans la description" value="<?= htmlspecialchars($search) ?>">  <!-- Change placeholder to reflect description search -->
        <button type="submit">🔍</button>
        <a href="projets_fines.php" class="financed-projects-btn">Les projets déjà financés</a>
    </form>

    <?php if (count($projects) > 0): ?>
        <?php foreach ($projects as $project): ?>
            <div class="project-card">
                <h3><?= htmlspecialchars($project['titre']) ?></h3>
                <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                <p><strong>Montant collecté:</strong> <?= $project['montant_total_collecte'] ?> dt</p>
                <p><strong>Objectif:</strong> <?= $project['montant_total_a_collecter'] ?> dt</p>
                <a href="detailspourdonateur.php?id=<?= $project['id_projet'] ?>" class="details-btn">More details</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun projet trouvé pour "<?= htmlspecialchars($search) ?>".</p>
    <?php endif; ?>
</div>
</body>
</html>
