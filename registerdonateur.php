<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $cin = $_POST['cin'];
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conn = new mysqli("localhost", "root", "", "helphub");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $checkPseudoSql = "SELECT COUNT(*) FROM donateur WHERE pseudo = ?";
    $stmt = $conn->prepare($checkPseudoSql);
    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $stmt->bind_result($countPseudo);
    $stmt->fetch();
    $stmt->close();
    if ($countPseudo > 0) {
        echo "<script>
                alert('❌ Le pseudo est déjà utilisé, veuillez en choisir un autre!');
                window.location.href = 'regs.html';
              </script>";
        exit();
    }
    $checkCinSql = "SELECT COUNT(*) FROM donateur WHERE cin = ?";
    $stmt = $conn->prepare($checkCinSql);
    $stmt->bind_param("s", $cin);
    $stmt->execute();
    $stmt->bind_result($countCin);
    $stmt->fetch();
    $stmt->close();
    if ($countCin > 0) {
        echo "<script>
                alert('❌ Le CIN est déjà utilisé, veuillez en choisir un autre!');
                window.location.href = 'regs.html';
              </script>";
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO donateur (cin, pseudo, nom, prenom, email, pwrd) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cin, $pseudo, $nom, $prenom, $email, $password);

    if ($stmt->execute()) {
        echo "<script>
                alert('✅ Registration successful!');
                window.location.href = 'index.html';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
