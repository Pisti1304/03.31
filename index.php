<?php
session_start();
include('./includes/dbvezerlo.php'); // A helyes fájlnevet add meg!

if (isset($_POST['belep'])) {  // Javított gombnév
    $email = $_POST['fnev'];  // Javított mezőnév
    $password = $_POST['passwd']; // Javított mezőnév

    $dbvez = new DBVezerlo();
   
    // Keresünk admin felhasználót az adatbázisban
    $query = "SELECT fnev, passwd FROM admin WHERE fnev = ?";
    $eredmeny = $dbvez->executeSelectQuery($query,
    [$email], "s");

    if (!empty($eredmeny)) {
        $hashed_password = $eredmeny[0]['passwd'];

        // Jelszó ellenőrzés
        if (password_verify($password, $hashed_password)) {
            $_SESSION['belepett'] = $email;
            header('Location: dashboard.php'); // Itt állítsd be a belépés utáni oldalt
            exit();
        } else {
            echo "<script>alert('Hibás jelszó');</script>";
        }
    } else {
        echo "<script>alert('Hibás felhasználónév');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Belépés</title>
</head>
<body>
    <h1>Belépés az adminfelületre</h1>
    <form method="post">
        <label for="fnev">Felhasználónév</label>
        <input type="text" placeholder="Felhasználónév" name="fnev" required>
       
        <label for="passwd">Jelszó</label>
        <input type="password" placeholder="Jelszó" name="passwd" required>
       
        <button name="belep" type="submit">BELÉPÉS</button>
    </form>

    <p><a href="../index.php">Vissza a főoldalra</a></p>
</body>
</html>