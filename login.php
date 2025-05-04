<?php
session_start();
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $pwd = trim($_POST['password'] ?? '');

    if ($pseudo === '' || $pwd === '') {
        echo "<script>window.location.href = 'index.html?login=empty';</script>";
        exit;
    }
    try {
        $stmt = $pdo->prepare("SELECT * FROM donateur WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $donateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($donateur && $pwd === $donateur['pwrd']) {
            $_SESSION['pseudo'] = $donateur['pseudo'];
            $_SESSION['id_donateur'] = $donateur['id_donateur'];
            header("Location: pagedonateur.php");
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM reponsable WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $responsable = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($responsable && $pwd === $responsable['pwrd']) {
            $_SESSION['pseudo'] = $responsable['pseudo'];
            $_SESSION['id_responsable'] = $responsable['id_responsable'];
            
            header("Location: association_dashboard.php");
            exit;
        }

        echo "<script>window.location.href = 'index.html?login=not_found';
        alert('incorrect') </script>";

    } catch (PDOException $e) {
        echo "<script>alert('Erreur de connexion à la base de données.');</script>";
    }
}
?>
