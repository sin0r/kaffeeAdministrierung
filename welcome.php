<?php
/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 11.12.2017
 * Time: 11:17
 */
require_once 'database.php';
session_start();
$username = $_SESSION["username"];

?>
    <head>
        <meta charset="UTF-8">
        <title>Willkommen!</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
              integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
              crossorigin="anonymous">
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Kaffee Administrierung</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="index.php">Startseite</a>
                <a class="nav-item nav-link" href="testSite.php">testsite</a>
                <a class="nav-item nav-link active" href="welcome.php">Home</a>
                <a class="nav-item nav-link" href="addBudget.php">Guthaben aufladen</a>
                <a class="nav-item nav-link" href="logout.php">Logout</a>
                <!-- <a class="nav-item nav-link disabled" href="#">Disabled</a> -->
            </div>
        </div>
    </nav>
    <br>

<?php

echo '<div class="content">';
try {

    $database = new database("localhost", "root", "", "coffeeAdministration");
    $DB_host = "localhost";
    $DB_user = "root";
    $DB_pass = "";
    $DB_name = "coffeeAdministration";

    try {
        $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
        $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statementToGetUserRoleAndName = $DB_con->prepare('SELECT Konsument.Rolle, Konsument.Name FROM Konsument WHERE Konsument.Name = :username');
        $statementToGetUserRoleAndName->bindParam(':username', $username);
        $statementToGetUserRoleAndName->execute();
        $roleAndName = $statementToGetUserRoleAndName->fetch(PDO::FETCH_ASSOC);

        if ($roleAndName['Rolle'] == 'Admin') {
            $stmt = $DB_con->prepare('SELECT Konsument.ID, Konsument.Name, Buchung.Betrag, Buchung.Datum, Buchungsart.Bezeichnung AS Art FROM `Buchung` INNER JOIN Konsument ON Buchung.KonsumentID = Konsument.ID INNER JOIN Buchungsart ON Buchung.BuchungsArt = Buchungsart.ID');
            $stmt->execute();
            $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<h5>Herzlich Willkommen, ' . $roleAndName['Name'] . '.</h5>
        <br><h6>Zuletzt getätigte Transaktionen aller Konsumenten:</h4>
        <br>';
            if ($stmt->rowCount() > 0) {
                echo '<table class="table table-striped">';
                echo '<th>Name</th><th>Wert</th><th>Datum</th><th>Buchungsart</th>';
                for ($i = 0; $i < $stmt->rowCount(); $i++) {
                    echo '<tr>';
                    echo '<td>' . $userRow[$i]['Name'] . '</td>';
                    echo '<td>' . $userRow[$i]['Betrag'] . '€</td>';
                    echo '<td>' . $userRow[$i]['Datum'] . '</td>';
                    if ($userRow[$i]['Art'] == 1) {
                        echo '<td> Überweisung </td>';
                    }
                    else {
                        echo '<td> Abbuchung </td>';
                    }
                    echo '</tr>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Es wurden bisher noch keine Transaktionen ausgeführt.';
            }
        } else {
            $stmt = $DB_con->prepare('SELECT Konsument.ID, Konsument.Name, Buchung.Betrag, Buchung.Datum, Buchung.BuchungsArt AS Art FROM `Buchung` INNER JOIN Konsument ON Buchung.KonsumentID = Konsument.ID INNER JOIN Buchungsart ON Buchung.BuchungsArt = Buchungsart.ID
                WHERE Konsument.Name = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<h3>Herzlich Willkommen, ' . $roleAndName['Name'] . '.</h3>
        <br><h4>Zuletzt von Ihnen getätigte Transaktionen:</h4>
        <br>';
            if ($stmt->rowCount() > 0) {
                echo '<table class="table table-striped">';
                echo '<th>Name</th><th>Wert</th><th>Datum</th><th>Buchungsart</th>';
                for ($i = 0; $i < $stmt->rowCount(); $i++) {
                    echo '<tr>';
                    echo '<td>' . $userRow[$i]['Name'] . '</td>';
                    echo '<td>' . $userRow[$i]['Betrag'] . '€</td>';
                    echo '<td>' . $userRow[$i]['Datum'] . '</td>';
                    if ($userRow[$i]['Art'] == 1) {
                        echo '<td> Überweisung </td>';
                    }
                    else {
                        echo '<td> Abbuchung </td>';
                    }
                    echo '</tr>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Sie haben bisher noch keine Transaktionen ausgeführt.';
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
echo '</div>';
echo '</body>';