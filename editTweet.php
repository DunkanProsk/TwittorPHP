<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: signin.php");
}

$idTweet = $_GET['id_tweet'];
$id = $_SESSION['id'];

$selectDataUser = "SELECT idCreator FROM Tweets WHERE id='$idTweet'";
$idCreatorTweet = FetchAssoc($selectDataUser)['idCreator'];

if ($id == $idCreatorTweet || $id == "38") {
    $selectTweet = "SELECT * FROM Tweets WHERE id='$idTweet'";
    $tweetData = FetchAssoc($selectTweet);

    $selectTags = "SELECT id_tag FROM TweetHashtag WHERE id_tweet='$idTweet'";
    $tagIdData = ConnectDB($selectTags);
    $titleTags = "";

    foreach($tagIdData as $tag) {
        $idTag = $tag['id_tag'];
        $selectTagsTitle = "SELECT title FROM Hashtags WHERE id='$idTag'";
        $titleTags = $titleTags . FetchAssoc($selectTagsTitle)['title'] . " ";
    }

    $titleTags = trim($titleTags);
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
    <title>Edit tweet</title>
</head>
<body>
    <div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='index.php'" src="img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='index.php'">TWITTOR</div>
        <img class="Content__header__profile" src="img/Profile.svg" alt="Login" onclick="document.location='signin.php'" height="20px">
    </div>
    <div class="Content__center__tweets">
        <form class="Content__form__tweets" action="editTweetLogic.php?<?='id_tweet='. $idTweet?>" method="POST">
            <h3>Edit tweet</h3>
            <textarea class="Content__form__textaria" placeholder="Your text" name="text" type="text" size="40"><?=$tweetData['tweetText']?></textarea><br>
            <button class="Content__form__button" type="submit">SEND</button><br>
        </form>
    </div>
</body>
</html>
