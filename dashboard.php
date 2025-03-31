<?php
session_start();
if(isset($_SESSION['beleptett'])){
    header("Location:index.php");
    exit();
}
if(isset($_GET['logout'])){
    session_destroy();
    header("location:index.php");
    exit();
}
$menupont = 1;
if(isset($_GET['mm']) && is_numeric($_GET['m'])){
    $menupont = intval($_GET['m']);
}

$menu=[
    ["id"=> 1, "nev" => "uj_felh", "title" => "Felhasználó"],
    ["id"=> 2, "nev"=> "jarmuvek", "title"=> "Jármű lista"],
    ["id"=> 3, "nev"=> "Eladasok", "title"=> "Eladások"]
];

$tartalom = [
    ["menu_id" => 1, "cim" =>"Új felhasználó", "tartalom" => "Felhasználó regisztráció"],
    ["menu_id" => 1, "cim" =>"Jelszó módosítása", "tartalom" => "Itt módosíthatod a jelszavad"],
    ["menu_id" => 2, "cim" =>"Új jármű", "tartalom" => "Új jármű felvitele: <a href='ujjarmu.php>itt felviheted az új járművet</a>'"],
    ["menu_id" => 2, "cim" =>"Új felhasználó", "tartalom" => "Meglévő járművek listája: <a href='jarmuvek.php'>Itt megnézheted a járműveket</a>"],
    ["menu_id" => 3, "cim" =>"Új felhasználó", "tartalom" => "Eladások statisztikái"]
];
if(!in_array($menupont, array_column($menu,"id"))){
    $menupont = 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($menu[$menupont-1]["title"]);?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <?php
        foreach($menu as $item){
            $active = ($item["id"] == $menupont) ?"active":"";
            echo"<a class='$active'href='?m=".$item["id"]."'>".
            htmlspecialchars($item["nev"]) . "</a>";
               }
               ?>
               <a href="?logout=true" class="logout">Kilépés</a>        
    </nav>
    
    <main>
        <?php
        foreach($tartalom as $t){
            if($t["menu_id"] == $menupont){
                echo "<h2>" . htmlspecialchars($t["cim"]) ."</h2>";
                echo "<p>" . htmlspecialchars($t["tartalom"]) ."<p>";
            }
            if($menupont == 2 && $t["cim"] == "Új jármű"){
                include("./ujjarmu.php");
            }
            if($menupont == 2 && $t["cim"] == "Jármű lista"){
                include("./jarmuvek_lista.php");
            }
        }
        ?>
    </main>
</body>
</html>