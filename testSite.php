<?php
/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 19.12.2017
 * Time: 15:24
 */
?>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<?php
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "coffeeAdministration";
$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statementToGetUserRoleAndName = $DB_con->prepare('SELECT konsument.Rolle, konsument.Name FROM konsument WHERE konsument.Name = :username');
$statementToGetUserRoleAndName->bindParam(':username', $username);
$statementToGetUserRoleAndName->execute();
$roleAndName = $statementToGetUserRoleAndName->fetch(PDO::FETCH_ASSOC);

if ($roleAndName != false) {
    echo '<br><h3>Herzlich Willkommen, ' . $roleAndName['Name'] . '.</h3>';
} else {
    echo '<br><h3>Herzlich Willkommen!</h3>';
}
?>
<div class="row h-25">
    <div class="col-sm-4 border">
        <div class="scrollable">
            Hier können Sie Ihr Guthaben um den gewünschten Betrag erweitern.
            <?php
            session_start();
            $username = $_SESSION["username"];

            $DB_host = "localhost";
            $DB_user = "root";
            $DB_pass = "";
            $DB_name = "coffeeAdministration";
            $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
            $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtToGetConsumerId = $DB_con->prepare('SELECT konsument.ID from konsument WHERE konsument.Kuerzel = :username');
            $stmtToGetConsumerId->execute([':username' => $username]);
            $userRowToGetConsumerId = $stmtToGetConsumerId->fetch(PDO::FETCH_ASSOC);
            $userId = $userRowToGetConsumerId['ID'];

            try {

                try {
                    $stmt = $DB_con->prepare('SELECT buchungen.Betrag, buchungen.art_fk FROM `buchungen`
            INNER JOIN konsument ON buchungen.Konsument_fk = konsument.ID WHERE konsument.Kuerzel = :username');
                    $stmt->execute(array(':username' => $username));
                    $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $budget = 0;
                    if ($stmt->rowCount() > 0) {
                        // var_dump($userRow);

                        for ($i = 0; $i < $stmt->rowCount(); $i++) {
                            if ($userRow[$i]['art_fk'] == 1) {
                                $budget = $budget + $userRow[$i]['Betrag'];
                            } else {
                                $budget = $budget - $userRow[$i]['Betrag'];
                            }
                        }
                        echo '<br><br>Aktueller Kontostand: ' . $budget . '€';

                    } else {
                        echo '<br><br>Aktueller Kontostand: ' . $budget . '€<br><br>Sie haben bisher noch keine Transaktionen getätigt.';
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
            <br><br>
            <div class="content">
                <h6>Guthaben aufladen/abbuchen:</h6>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($transaction_err)) ? 'has-error' : ''; ?>">
                        <label>Transaktionsart:</label>
                        <select name="transaction">
                            <option value="1">Überweisung</option>
                            <option value="2">Abbuchung</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Betrag:</label>
                        <input type="text" name="amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Guthaben aktualisieren"
                               name="doTransaction">
                    </div>

                </form>
            </div>
            <?php
            if (isset($_POST['doTransaction'])) { // button name
                $transactionStmt = $DB_con->prepare('INSERT INTO buchungen (Datum, Betrag, Konsument_fk, art_fk)
        VALUES (CURRENT_TIMESTAMP, :betrag, :konsumentId, :artId)');
                $transactionStmt->execute([':betrag' => $_POST["amount"], ':konsumentId' => $userId, 'artId' => $_POST['transaction']]);
                echo 'Ihr Guthaben wurde erfolgreich aktualisiert.';
                //header("Location = addBudgetSuccess.php");
            } else {

            }
            ?>
        </div>
    </div>
    <div class="col-sm-4 content border">
        <div class="scrollable">
            <?php
            try {

                // $database = new database("localhost", "root", "", "coffeeAdministration");
                $DB_host = "localhost";
                $DB_user = "root";
                $DB_pass = "";
                $DB_name = "coffeeAdministration";

                try {
                    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
                    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $statementToGetUserRoleAndName = $DB_con->prepare('SELECT konsument.Rolle, konsument.Name FROM konsument WHERE konsument.Kuerzel = :username');
                    $statementToGetUserRoleAndName->bindParam(':username', $username);
                    $statementToGetUserRoleAndName->execute();
                    $roleAndName = $statementToGetUserRoleAndName->fetch(PDO::FETCH_ASSOC);

                    if ($roleAndName['Rolle'] == '1') {
                        $stmt = $DB_con->prepare('SELECT konsument.Kuerzel, konsument.Name, buchungen.Betrag, buchungen.Datum FROM `buchungen` INNER JOIN konsument ON buchungen.Konsument_fk = konsument.ID');
                        $stmt->execute();
                        $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo '<h6>Zuletzt getätigte Transaktionen aller Konsumenten:</h4><br>';
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
                            echo 'Es wurden bisher noch keine Transaktionen ausgeführt.';
                        }
                    } else {
                        $stmt = $DB_con->prepare('SELECT konsument.Kuerzel, konsument.Name, buchungen.Betrag, buchungen.Datum FROM `buchungen` INNER JOIN konsument ON buchungen.Konsument_fk = konsument.ID
        WHERE konsument.Kuerzel = :username');
                        $stmt->bindParam(':username', $username);
                        $stmt->execute();
                        $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        echo '<h4>Zuletzt von Ihnen getätigte Transaktionen:</h4><br>';
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
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
    </div>
    <div class="col-sm-4">.col-sm-4</div>
</div>