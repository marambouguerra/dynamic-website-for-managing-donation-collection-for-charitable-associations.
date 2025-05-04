<?php
session_start();
if (!isset($_SESSION['id_responsable'])) {
    echo "<script>alert('⚠️ Vous devez être connecté pour accéder à cette page.'); window.location.href='index.html';</script>";
    exit;
}
$host = 'localhost';
$dbname = 'helphub';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}
$id = $_SESSION['id_responsable'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $pword = $_POST['password'];
    $nom_association = $_POST['org_name'];
    $adresse_association = $_POST['address'];
    $matricule_fiscale = $_POST['fiscale'];
    $cin = $_POST['cin'];
    $logoUpdate = "";
    $params = [];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = file_get_contents($_FILES['logo']['tmp_name']);
        $logoUpdate = ", logo = :logo";
        $params[':logo'] = $logo;
    }

    $sql = "UPDATE reponsable SET 
            nom = :nom,
            prenom = :prenom,
            email = :email,
            nom_association = :nom_association,
            adresse_association = :adresse_association,
            matricule_fiscal = :matricule_fiscal,
            pseudo = :pseudo,
            pwrd = :pword,
            cin = :cin
            $logoUpdate
            WHERE id_responsable = :id";

    $stmt = $pdo->prepare($sql);
    $params += [
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':nom_association' => $nom_association,
        ':adresse_association' => $adresse_association,
        ':matricule_fiscal' => $matricule_fiscale,
        ':pseudo' => $pseudo,
        ':pword' => $pword,
        ':cin' => $cin,
        ':id' => $id
    ];

    if ($stmt->execute($params)) {
        echo "<script>alert('✅ Mise à jour réussie !'); window.location.href = 'association_dashboard.php';</script>";
        exit;
    } else {
        echo "<p style='color:red;'>❌ Erreur lors de la mise à jour.</p>";
    }
}
$stmt = $pdo->prepare("SELECT * FROM reponsable WHERE id_responsable = ?");
$stmt->execute([$id]);
$responsable = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$responsable) {
    echo "<script>alert('❌ Utilisateur introuvable.'); window.location.href='index.html';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            🔄 Modifier votre profil
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nom:</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($responsable['nom']) ?>">
            </div>
            <div class="form-group">
                <label>Prénom:</label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($responsable['prenom']) ?>">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($responsable['email']) ?>">
            </div>
            <div class="form-group">
                <label>Nom Association:</label>
                <input type="text" name="org_name" value="<?= htmlspecialchars($responsable['nom_association']) ?>">
            </div>
            <div class="form-group">
                <label>Adresse:</label>
                <input type="text" name="address" value="<?= htmlspecialchars($responsable['adresse_association']) ?>">
            </div>
            <div class="form-group">
                <label>Matricule Fiscale:</label>
                <input type="text" name="fiscale" value="<?= htmlspecialchars($responsable['matricule_fiscal']) ?>">
            </div>
            <div class="form-group">
                <label>Pseudo:</label>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($responsable['pseudo']) ?>">
            </div>
            <div class="form-group">
                <label>Mot de passe:</label>
                <input type="password" name="password" value="<?= htmlspecialchars($responsable['pwrd']) ?>">
            </div>
            <div class="form-group">
                <label>CIN:</label>
                 <input type="text" name="cin" value="<?= htmlspecialchars($responsable['CIN']) ?>"><br>
            </div>
            <div class="form-group">
                <label>Logo actuel:</label><br>
                <?php if ($responsable['logo']): ?>
                    <img src="data:image/png;base64,<?= base64_encode($responsable['logo']) ?>" alt="Logo" width="120"><br>
                <?php else: ?>
                    <p>Aucun logo</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Changer Logo:</label>
                <input type="file" name="logo" accept="image/*">
            </div>
            <div class="button-container">
                <button type="submit">💾 Enregistrer les modifications</button><br>
                <a href="association_dashboard.php" class="back-button">← Retour au tableau de bord</a>
            </div>
        </form>
    </div>
</body>
</html>
