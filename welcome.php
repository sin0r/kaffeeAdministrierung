<?php
/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 11.12.2017
 * Time: 11:17
 */
session_start();
$username = $_SESSION["username"];

?>
    <head>
        <meta charset="UTF-8">
        <title>Welcome!</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <link rel="stylesheet" href="main.css">
    </head>
    <body>

<?php

echo '<div class="wrapper">';
try {
    $DB_host = "localhost";
    $DB_user = "root";
    $DB_pass = "";
    $DB_name = "coffeeAdministration";

    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmt = $DB_con->prepare('SELECT konsument.Kuerzel, konsument.Name, buchungen.Betrag, buchungen.Datum FROM `buchungen` INNER JOIN konsument ON buchungen.Konsument_fk = konsument.ID');
        $stmt->execute();
        $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<h3>Herzlich Willkommen, ' . $userRow['Name'] . '.</h3>
        <br><h4>Zuletzt getätigte Transaktionen:</h4>
        <br>';
        if ($stmt->rowCount() > 0) {
            echo '<table class="table table-striped">';
            echo '<th>Name</th><th>Wert</th><th>Datum</th>';
            for ($i = 0; $i < $stmt->rowCount(); $i++) {
                echo '<tr>';
                echo '<td>' . $userRow[$i]['Name'] . '</td>';
                echo '<td>' . $userRow[$i]['Betrag'] . '€</td>';
                echo '<td>' . $userRow[$i]['Datum'] . '</td>';
                echo '</tr>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'Sie haben bisher noch keine Transaktionen ausgeführt.';
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
echo '</div>';
echo '</body>';