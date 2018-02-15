<?php
/**
 * Created by PhpStorm.
 * User: s.lory
 * Date: 14.12.2017
 * Time: 14:20
 */
?>
<head>
    <meta charset="UTF-8">
    <title>Guthaben bearbeiten</title>
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
            <a class="nav-item nav-link" href="welcome.php">Home</a>
            <a class="nav-item nav-link active" href="addBudget.php">Guthaben aufladen</a>
            <a class="nav-item nav-link" href="#">Logout</a>
            <!-- <a class="nav-item nav-link disabled" href="#">Disabled</a> -->
        </div>
    </div>
</nav>
<br>
<div class="wrapper">
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

    $stmtToGetConsumerId = $DB_con->prepare('SELECT Konsument.ID from Konsument WHERE Konsument.Name = :username');
    $stmtToGetConsumerId->execute([':username' => $username]);
    $userRowToGetConsumerId = $stmtToGetConsumerId->fetch(PDO::FETCH_ASSOC);
    $userId = $userRowToGetConsumerId['ID'];

    try {

        try {
            $stmt = $DB_con->prepare('SELECT Buchung.Betrag, Buchung.BuchungsArt FROM `Buchung`
            INNER JOIN Konsument ON Buchung.KonsumentID = Konsument.ID WHERE Konsument.Name = :username');
            $stmt->execute(array(':username' => $username));
            $userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $budget = 0;
            if ($stmt->rowCount() > 0) {
                // var_dump($userRow);

                for ($i = 0; $i < $stmt->rowCount(); $i++) {
                    if ($userRow[$i]['BuchungsArt'] == 1) {
                        $budget = $budget + $userRow[$i]['Betrag'];
                    } else {
                        $budget = $budget - $userRow[$i]['Betrag'];
                    }
                }
                echo '<br><br>Aktueller Kontostand: ' . $budget . '€';

            } else {
                echo '<br><br>Aktueller Kontostand: ' . $budget . '€<br><br>Sie haben bisher noch keine Transaktionen getätigt.</div>';
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
            <div class="form-group <?php echo (!empty($budget_err)) ? 'has-error' : ''; ?>">
            <label>Betrag:</label>
            <input type="text" name="amount" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Guthaben aktualisieren" name="doTransaction">
            </div>
    </div>
    </form>
</body>
</html>
<?php
if(isset($_POST['doTransaction'])){ // button name
    $transactionStmt = $DB_con->prepare('INSERT INTO Buchung (KonsumentID, BuchungsArt, Datum, Betrag)
        VALUES (:konsumentId, :artId, CURRENT_TIMESTAMP, :betrag)');
    $transactionStmt->execute([':betrag' => $_POST["amount"], ':konsumentId' => $userId, 'artId' => $_POST['transaction']]);
    echo 'Ihr Guthaben wurde erfolgreich aktualisiert.';
    //header("Location = addBudgetSuccess.php");
}else{

}
    ?>