<?php
if(!isset($_SESSION["beleptett"])) {
    header("Location: index.php");
    exit();
}

include('includes/dbvezerlo.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['marka'],$_POST['evjarat'],$_POST['ar'],$_POST['tipus']) &&
    !empty($_POST['marka']) && !empty($_POST['evjarat']) && !empty($_POST['ar'])
    && !empty($_POST['tipus'])){

        $jarmu_nev = $_POST['marka'];
        $evjarat = $_POST['evjarat'];
        $ar = $_POST['ar'];
        $tipus_id  = $_POST['tipus'];
        $image_dir = '';
        $image_path = '';

        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
            $file_tmp_name = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_extensions = ['jpg','jpeg','png','gif'];
            if(in_array($file_ext, $allowed_extensions)){
                $dbvez = new DBVezerlo();
                $query = "SELECT tipus FROM jarmu_tip WHERE id = ?";
                $tipus = $dbvez->executeSelectQuery($query,[$tipus_id], "i");

                if(!empty($tipus)){
                    $tipus = $tipus[0]['tipus'];
                    $image_dir ='pic/'. $tipus . '/';
                    if(!file_exists($image_dir)){
                        mkdir($image_dir,0777, true);
                }
                $image_path = $image_dir . basename($file_name);
                if(!move_uploaded_file($file_tmp_name, $image_path)){
                    echo"Hiba történt a fájl feltöltésekor!";
                    exit();
                }

        }
    } else{
        echo "Érvénytelen fájl típus!";
        exit();
    }
        } else{
            echo "Nincs kivájlasztott fájl!";
            exit();
        }

        $query = "INSERT INTO jarmuvek(marka,evjarat,ar,tipus_id,kep) VALUES(?,?,?,?,?)";
        $params = [$jarmu_nev,$evjarat,$ar,$tipus_id,$image_path ];
        $dbvez->executeQuery($query, $params,"sdiis");

        $last_id = $dbvez->getInsertId();

        $query = "SELECT * FROM jarmuvek WHERE id = ?";
        $jarmu = $dbvez->executeSelectQuery($query, [$last_id],"i");
        $jarmu = $jarmu[0];


        echo"<p>Az új autó felvétele megtörtént!</p>";
        echo "<p>márka: " . htmlspecialchars($jarmu['marka']) . "</p>" ;
        echo "<p>Évjárat: " . htmlspecialchars($jarmu['evjarat']) . "</p>" ;
        echo "<p>Ár: " . htmlspecialchars($jarmu['ar']) . " FT</p>" ;
        echo "<img src='" . htmlspecialchars($jarmu['kep']) . "'alt='Jármű kép' width='100'>" ;
        echo "<meta http-equiv='refresh' content='8;url=dashboard.php?m=2'>";

    } else{
        echo "Hiba történt: nem mindenmező lett kitöltve!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új jármű felvitele</title>
</head>
<body>
    <h2>Új jármű felvitele</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="marka">Járű márkája</label>
        <input type="text" name="marka" required><br><br>

        <label for="evjarat">Évjárat:</label>
        <input type="text" name="evjarat" required><br><br>

        <label for="ar">Ár:</label>
        <input type="text" name="ar" step="0.01" required><br><br>

        <label for="tipus">Jármű típus</label>
        <select name="tipus" required>
            <?php 
            $dbvez = new DBVezerlo();
            $query = "SELECT * FROM jarmu_tip";
            $tipusok = $dbvez->executeSelectQuery($query,[]);
            foreach($tipusok as $tipus){
                echo "<option value='" . $tipus['id'] . htmlspecialchars($tipus['tipus']) . '</option>';
            }
            ?>
        </select><br><br>

        <label for="image">Kép feltöltése:</label>
        <input type="file" name="image" accept="image/*" required><br><br>

        <button type="submit">Jármű feltöltése</button>
    </form>
</body>
</html>