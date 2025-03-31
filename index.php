<?php
session_start();
include('./includes/dbvezerlo.php');

if(isset($_POST['belep'])){
    $email = $_POST['fnev'];
    $password = $_POST['passwd'];

    $dbvez = new DBVezerlo();
    $query = "SELECT fnev,passwd FROM admin WHERE fnev = ?";
    $eredmeny = $dbvez->executeSelectQuery($query,[$email],"s");

    if(!empty($eredmeny)){
        $hashed_password = $eredmeny[0]["passwd"];
    
    if(password_verify($password,$hashed_password)){
        $_SESSION['beleptett'] = $email;
        header('Location:dashboard.php');
        exit();
    }else{
        echo"<script>alert('Hibás jelszó');</script>";
    }
    }
    else{
        echo "<script>alert('Hibás felhasznalónév');</script>";
        
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>Belépés az admmin felületre</h1>
    <form method="post">
    <label for="fnev">Felhasználó</label>
    <input type="text" placeholder="Felhasználónév" name="fnev" required>

    <label for="passwd">Jelszó</label>
    <input type="text" placeholder="Jelszó" name="passwd" required>

    <button name="belep" type="submit">BELÉPÉS</button>
    </form>
    <p><a href="./index.php">Vissza a főoldalra</a></p>
</body>
</html>