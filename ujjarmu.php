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
    }
}