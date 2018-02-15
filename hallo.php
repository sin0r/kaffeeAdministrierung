<?php
/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 11.12.2017
 * Time: 11:17
 */
require_once 'database.php';
require_once ('jpgraph-4.2.0/src/jpgraph.php');
require_once ('jpgraph-4.2.0/src/jpgraph_line.php');
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
        <script src="main.js"></script>
    </head>
<body>
    <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Kaffee Administrierung</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="logout.php">Logout</a>
                <!-- <a class="nav-item nav-link disabled" href="#">Disabled</a> -->
            </div>
        </div>
    </nav>
    <br>
    </div>
    <?php
    $DB_host = "localhost";
    $DB_user = "root";
    $DB_pass = "";
    $DB_name = "coffeeAdministration";
    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statementToGetUserRoleAndName = $DB_con->prepare('SELECT Konsument.Rolle, Konsument.Name FROM Konsument WHERE Konsument.Name = :username');
    $statementToGetUserRoleAndName->bindParam(':username', $username);
    $statementToGetUserRoleAndName->execute();
    $roleAndName = $statementToGetUserRoleAndName->fetch(PDO::FETCH_ASSOC);

    $stmtToGetConsumerId = $DB_con->prepare('SELECT Konsument.ID from Konsument WHERE Konsument.Name = :username');
    $stmtToGetConsumerId->execute([':username' => $username]);
    $userRowToGetConsumerId = $stmtToGetConsumerId->fetch(PDO::FETCH_ASSOC);
    $userId = $userRowToGetConsumerId['ID'];

    if ($roleAndName != false) {
        echo '<br><h4>Willkommen zurück, ' . $roleAndName['Name'] . '!</h4>';
    } else {
        echo '<br><h4>Willkommen zurück!</h4>';
    }
    ?>

    <div class="row content">
        <div class="col-sm-3 ">
            <?php
            try {
            $stmt = $DB_con->prepare('SELECT Buchung.Betrag, Buchung.BuchungsArt FROM `Buchung`
            INNER JOIN Konsument ON Buchung.KonsumentID = Konsument.ID WHERE Konsument.Name = :username');
            $stmt->execute(array(':username' => $username));
            $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $budget = 0;
            if ($stmt->rowCount() > 0) {

                for ($i = 0; $i < $stmt->rowCount(); $i++) {
                    if ($userRow[$i]['BuchungsArt'] == 1) {
                        $budget = $budget + $userRow[$i]['Betrag'];
                    } else {
                        $budget = $budget - $userRow[$i]['Betrag'];
                    }
                }
                echo '<h3 id="budget">Aktueller Kontostand: ' . $budget . '€</h3>';

            } else {
            echo '<h3 id="budget">Aktueller Kontostand: ' . $budget . '€<br><br>Sie haben bisher noch keine Transaktionen getätigt.</h3></div>';
        }
        if ($budget < 1 && $budget > 0) {
                echo '<div class="alert alert-warning">Ihr Guthaben ist bald aufgebraucht.</div>';
        }
        elseif ($budget < 0) {
                echo '<div class="alert alert-danger">Sie sind bereits im Minus und sollten Ihr Guthaben bald auffüllen.</div>';
        }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        ?>
        <br><br>
            <h6>Guthaben aufladen/abbuchen:</h6>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($transaction_err)) ? 'has-error' : ''; ?>">
                    <label>Transaktionsart:</label>
                    <select name="transaction">
                        <option value="1">Überweisung</option>
                        <option value="2">Abbuchung</option>
                    </select>
                </div>
                <div class="form-group <?php echo (!empty($budget_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="amount" placeholder="Betrag" class="form-control col-xs-2">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Guthaben aktualisieren" name="doTransaction">
                </div>
            </form>
            <?php
            if(isset($_POST['doTransaction'])){ // button name
                $transactionStmt = $DB_con->prepare('INSERT INTO Buchung (KonsumentID, BuchungsArt, Datum, Betrag)
                VALUES (:konsumentId, :artId, CURRENT_TIMESTAMP, :betrag)');
                $transactionStmt->execute([':betrag' => $_POST["amount"], ':konsumentId' => $userId, 'artId' => $_POST['transaction']]);
                echo 'Ihr Guthaben wurde erfolgreich aktualisiert.';
                header('Location: ' . $_SERVER['PHP_SELF']);
                die('<META http-equiv="refresh" content="0;URL=' . $_SERVER['PHP_SELF'] . '">');
            }else{

            }?>

        </div>







        <div class="col-sm-6 scrollable">
            <?php
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

                    echo '<h6>Zuletzt getätigte Transaktionen aller Konsumenten:</h4>
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

                    echo '<h6>Zuletzt von Ihnen getätigte Transaktionen:</h6>
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
            ?>
        </div>
        <div class="col-sm-3 well">
           <?php if ($roleAndName['Rolle'] == 'Admin') {

           }
           ?>
        </div>
    </div>


    <br><br><br>
<div>
    <div class="d-inline-flex p-2 well">I'm an inline flexbox container!</div>
</div>


</body>