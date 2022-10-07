<?php

include "./funcs.php";

if (!empty($_SESSION)) {
    header("location: ./loginout.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="shortcut icon" href="/img/Favicon.svg" type="image/x-icon">
    <title>Sign in</title>
</head>
<body>
    <div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='index.php'" src="img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='index.php'">TWITTOR</div>
        <img class="Content__header__profile" src="img/Profile.svg" alt="Login" onclick="document.location='signin.php'" height="20px">
    </div>
    <div class="Content__center">
        <form class="Content__form" action="signin.php" method="POST">
            <h3>Sign in</h3>
            <input placeholder="Your Nickname" name="nickname" type="text" size="40"><br>
            <input placeholder="Your Password" name="pass" type="password" size="40"><br>
            <button class="Content__form__button" type="submit">SEND</button><br>
            <a href="/signup.php">Sign Up</a>
        </form>
    </div>
</body>
</html>

<?php

$nickname = $_POST ? $_POST['nickname'] : null;

$selectSQLUser = "SELECT id, userNickname, passHash FROM Users WHERE userNickname='$nickname'";

$userData = FetchAssoc($selectSQLUser);

$id = $userData ? $userData['id'] : null;

if ($_POST) {
    if ($_POST['pass'] != '') {
        $pass = password_verify($_POST['pass'], $userData['passHash']);
    
        if ($pass) {
            $_SESSION = array("id"=>$id);
            header("location: ./index.php");
        } else {
            echo "<div>Неверный пароль</div>";
        }
    }
}

?>
