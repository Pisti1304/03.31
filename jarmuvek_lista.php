<?php
//session_start();
if (!isset($_SESSION['belepett'])) {
    header("Location: dashboard.php?m=2");
    exit();
}

//include_once('includes/dbvezerlo.php');

// Járművek lekérése az adatbázisból
$dbvez = new DBVezerlo();
$query = "SELECT j.id, j.marka, j.evjarat, j.ar, t.tipus, j.kep FROM jarmuvek j
 JOIN jarmu_tip t ON j.tipus_id = t.id";
$jarmuvek = $dbvez->executeSelectQuery($query, []);

// Ellenőrizhetjük, hogy vannak-e járművek a listában
if (empty($jarmuvek)) {
    echo "<p>Nincsenek járművek a rendszerben!</p>";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/jarmu_list.css">
    <title>Járművek listája</title>
</head>
<body>

<h2>Járművek listája</h2>

<?php if (!empty($jarmuvek)): ?>
    <table>
        <thead>
            <tr>
                <th>Marka</th>
                <th>Évjárat</th>
                <th>Ár</th>
                <th>Típus</th>
                <th>Kép</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jarmuvek as $jarmu): ?>
                <tr>
                    <td><?php echo htmlspecialchars($jarmu['marka']); ?></td>
                    <td><?php echo htmlspecialchars($jarmu['evjarat']); ?></td>
                    <td><?php echo number_format($jarmu['ar'], 2,
                    ',', ' '); ?> Ft</td>
                    <td><?php echo htmlspecialchars($jarmu['tipus']); ?></td>
                    <td>
                        <?php if (!empty($jarmu['kep'])): ?>
                            <img src="<?php echo htmlspecialchars($jarmu['kep']); ?>"
                            alt="Jármű kép" width="100">
                        <?php else: ?>
                            <p>Nincs kép</p>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<br>

</body>
</html>
