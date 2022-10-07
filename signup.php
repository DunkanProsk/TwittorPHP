<?php

include "./funcs.php";

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
    <title>Sign Up</title>
</head>
<body>
    <div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='index.php'" src="img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='index.php'">TWITTOR</div>
        <img class="Content__header__profile" src="img/Profile.svg" alt="Login" onclick="document.location='signin.php'" height="20px">
    </div>
    <div class="Content__center">
        <form class="Content__form" enctype="multipart/form-data" action="signup.php" method="POST">
            <h3>Sign Up</h3>
            <input placeholder="Your Name" name="name" type="text" size="40"><br>
            <input placeholder="Your Nickname" name="nickname" type="text" size="40"><br>
            <input placeholder="Your Password" name="pass" type="password" size="40"><br>
            <textarea placeholder="About You" name="bio" type="text" size="40"></textarea><br>
            <input class="Content__form__file" name="photo" type="file" extension="png,jpg"><br>
            <button class="Content__form__button" type="submit">SEND</button><br>
            <a href="/signin.php">Login in</a>
        </form>
    </div>
</body>
</html>

<?php

$uploaddir = '/home/dunkan/Desktop/Nginx/img/';
$uniqFileName = uniqid() . '.jpg';
$uploadfile = $uploaddir . $uniqFileName;

$name = $_POST ? $_POST['name'] : null;
$nickname = $_POST ? $_POST['nickname'] : null;
$pass = $_POST ? password_hash($_POST['pass'], PASSWORD_DEFAULT) : null;
$bio = $_POST ? $_POST['bio'] : null;
$photo = $uniqFileName;

$selectNickname = "SELECT userNickname FROM Users WHERE userNickname='$nickname'";
$resultNick = FetchAssoc($selectNickname);

if ($_POST) {
    if ($_POST['nickname'] != '') {
        if (!empty($resultNick)) {
            echo "<div>Такой ник существует</div>";
        } else {
            if (empty($_FILES['photo']['tmp_name'])) {
                $photo = "UserNophoto.jpg";
            } else {
                move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile);
            }
            
            $insertSQLUser = "INSERT INTO Users (userName, userNickname, passHash, userBio, linkPhoto) VALUES ('$name','$nickname','$pass','$bio','$photo')";
            ConnectDB($insertSQLUser);
        
            $selectId = "SELECT id FROM Users WHERE userNickname='$nickname'";
            $id = FetchAssoc($selectId)['id'];
        
            $_SESSION = array("id"=>$id);
            header("location: ./index.php");
        }    
    }
}

?>
