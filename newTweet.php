<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: ./signin.php");
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
    <title>New tweet</title>
</head>
<body>
<div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='index.php'" src="img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='index.php'">TWITTOR</div>
        <img class="Content__header__profile" src="img/Profile.svg" alt="Login" onclick="document.location='signin.php'" height="20px">
    </div>
    <div class="Content__center__tweets">
        <form class="Content__form__tweets" action="newTweet.php" method="POST">
            <h3>Create new tweet</h3>
            <textarea class="Content__form__textaria" placeholder="Your text" name="text" type="text" size="40"></textarea><br>
            <input class="Content__form__input" placeholder="Введите тэги через пробел без #" name="hashtag" type="text" size="50"><br>
            <button class="Content__form__button" type="submit">SEND</button><br>
        </form>
    </div>
</body>
</html>

<?php

if (!empty($_POST)) {

    $id = $_SESSION['id'];
    $text = $_POST['text'];
    $hashtags = explode(" ", $_POST['hashtag']);

    $insertTweet = "INSERT INTO Tweets (idCreator, tweetText, tweetDateCreate) VALUES ('$id', '$text', CURRENT_TIMESTAMP)";
    ConnectDB($insertTweet);

    if (!empty($_POST['hashtag'])) {

        foreach ($hashtags as $key) {

            $selectTagsSQL = "SELECT * FROM Hashtags WHERE title='$key'";
    
            if (empty(FetchAssoc($selectTagsSQL))) {

                $insertTag = "INSERT INTO Hashtags (title) VALUE ('$key')";
                ConnectDB($insertTag);
            };
    
            $selectTag = "SELECT id FROM Hashtags WHERE title='$key'";
            $idTag = FetchAssoc($selectTag)['id'];
    
            $selectTweet = "SELECT id FROM Tweets WHERE tweetDateCreate=(SELECT MAX(tweetDateCreate) FROM Tweets)";
            $idTweet = FetchAssoc($selectTweet)['id'];
            
            $insertTwHsh = "INSERT INTO TweetHashtag (id_tweet, id_tag) VALUES ('$idTweet','$idTag')";
            ConnectDB($insertTwHsh);
        }

    }

    header("location: ./index.php");
}

?>
