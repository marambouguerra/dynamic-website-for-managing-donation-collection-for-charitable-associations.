<?php
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
 
    $matricule_fiscale = $_POST['fiscale'];
    $pseudo = $_POST['pseudo'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $pword = $_POST['password']; 
    $nom_association = $_POST['org_name'];
    $addresse_association = $_POST['address'];
    $cin = $_POST['cin'];
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = file_get_contents($_FILES['logo']['tmp_name']);
    }

    try {
        $checkPseudoSql = "SELECT COUNT(*) FROM reponsable WHERE pseudo = :pseudo";
        $stmt = $pdo->prepare($checkPseudoSql);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        $countPseudo = $stmt->fetchColumn();
        if ($countPseudo > 0) {
            echo "<script>
                    alert('❌ Le pseudo est déjà utilisé, veuillez en choisir un autre!');
                    window.location.href = 'registerres.html';
                  </script>";
            exit();
        }
        $checkCinSql = "SELECT COUNT(*) FROM reponsable WHERE cin = :cin";
        $stmt = $pdo->prepare($checkCinSql);
        $stmt->bindParam(':cin', $cin);
        $stmt->execute();
        $countCin = $stmt->fetchColumn();
        if ($countCin > 0) {
            echo "<script>
                    alert('❌ Le CIN est déjà utilisé, veuillez en choisir un autre!');
                    window.location.href = 'registerres.html';
                  </script>";
            exit();
        }
        $checkFiscalSql = "SELECT COUNT(*) FROM reponsable WHERE matricule_fiscal = :matricule_fiscale";
        $stmt = $pdo->prepare($checkFiscalSql);
        $stmt->bindParam(':matricule_fiscale', $matricule_fiscale);
        $stmt->execute();
        $countFiscal = $stmt->fetchColumn();
        if ($countFiscal > 0) {
            echo "<script>
                    alert('❌ Le matricule fiscal est déjà utilisé, veuillez en choisir un autre!');
                    window.location.href = 'registerres.html';
                  </script>";
            exit();
        }
        $sql = "INSERT INTO reponsable 
                (nom, prenom, email, nom_association, adresse_association, matricule_fiscal, logo, pseudo, pwrd, cin)
                VALUES 
                (:nom, :prenom, :email, :nom_association, :addresse_association, :matricule_fiscale, :logo, :pseudo, :pword, :cin)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nom_association', $nom_association);
        $stmt->bindParam(':addresse_association', $addresse_association);
        $stmt->bindParam(':matricule_fiscale', $matricule_fiscale);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_LOB);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':pword', $pword);
        $stmt->bindParam(':cin', $cin);

        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ Enregistrement réussi !');
                    window.location.href = 'index.html';
                  </script>";
            exit();
        } else {
            echo "<p style='color: red;'>❌ Échec de l'enregistrement.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>
