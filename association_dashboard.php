<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Projets</title>
    <link rel="stylesheet" href="style4.css">
</head>
<body>
    <header>
        <nav>
        
                <li id="logo"><a href="index.html"><img src="téléchargement__1_-removebg-preview.png"></a></li>

                
                <?php
session_start();
$logoHtml = "";

if (isset($_SESSION['id_responsable'])) {
    $pdo = new PDO("mysql:host=localhost;dbname=helphub;charset=utf8mb4", "root", "");
    $stmt = $pdo->prepare("SELECT logo FROM reponsable WHERE id_responsable = ?");
    $stmt->execute([$_SESSION['id_responsable']]);
    $responsable = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($responsable && $responsable['logo']) {
        $base64Logo = base64_encode($responsable['logo']);
        $logoHtml = "<a href='update_responsable.php'><img src='data:image/png;base64,{$base64Logo}' alt='Logo' class='profile-logo'></a>";
    } else {
        $logoHtml = "<a href='update_responsable.php'><img src='default-avatar.png' alt='Default' class='profile-logo'></a>";
    }
}
?>

<li class="profile-image"><?= $logoHtml ?></li>

        </nav>
    </header>

    <main>
        <div id="project-list" class="project-container">
           
            <?php include 'load_projects.php'; ?>
        </div>

        <div class="add-button">
            <a href="ajouter_projet.php" class="btn">ajouter projet</a>
        </div>
    </main>
</body>
</html>
