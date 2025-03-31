<?php
include('./includes/dbvezerlo.php');

$fnev = "admin";
$jelszo = "ujjelszo123";
$hash = password_hash($jelszo, PASSWORD_BCRYPT);

$dbvez = new DBVezerlo() ;
$query = "UPDATE adin SET passwd = ? WHERE fnev = ?" ;

if($dbvez->executeQuery($query,[$hash,$fnev],"ss")){
    echo "Admin jelszava frissítve";
}else   {
    echo "Hiba történt a frissítés során";
}