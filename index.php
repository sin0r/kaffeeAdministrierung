<?php

// require_once 'config.php';

$username = $password = "";
$username_err = $password_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty(trim($_POST["username"]))) {
        $username_err = 'Bitte geben Sie einen Usernamen ein.';
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST['password']))) {
        $password_err = 'Bitte geben Sie ein Passwort ein.';
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($username_err) && empty($password_err)) {

        $DB_host = "localhost";
        $DB_user = "root";
        $DB_pass = "";
        $DB_name = "coffeeAdministration";

        try {
            $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
            $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try {
                $stmt = $DB_con->prepare('SELECT `Kuerzel`, `Passwort`, `Rolle` FROM `Konsument` WHERE `Kuerzel` = :username');
                $stmt->execute(array(':username' => $username));
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($stmt->rowCount() > 0) {
                    // var_dump($userRow);
                    if ($password == $userRow['Passwort']) {
                        session_start();
                        $_SESSION['username'] = $username;
                        header("location: welcome.php");
                    } else {
                        echo '<div class="alert-danger">Bitte geben Sie einen validen Benutzernamen und Passwort ein.</div>';
                    }

                } else {
                    echo '<div class="alert-danger">Bitte geben Sie einen validen Benutzernamen und Passwort ein.</div>';
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<div class="wrapper">
    <h2>Login</h2>
    <p>Bitte geben Sie Ihre Login-Daten ein.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Kürzel:</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password:</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
    </form>
</div>
</body>
</html>