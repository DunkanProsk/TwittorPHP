<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: ./signin.php");
}

$idTweet = $_POST['idTweet'];

$selectSQLTweets = "SELECT * FROM Tweets WHERE id=$idTweet";
$resultTweet = FetchAssoc($selectSQLTweets);

$idCreator = $resultTweet['idCreator'];
$selectSQLDataUser = "SELECT * FROM Users WHERE id=$idCreator";
$userData = FetchAssoc($selectSQLDataUser);

$idCreator = $resultTweet['idCreator'];

$selectAlltitle = "SELECT id_tag FROM TweetHashtag WHERE id_tweet='$idTweet'";
$titleAll = ConnectDB($selectAlltitle);

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
    <title>Crate new retweet</title>
</head>
<body>
    <div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='index.php'" src="img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='index.php'">TWITTOR</div>
        <img class="Content__header__profile" src="img/Profile.svg" alt="Login" onclick="document.location='signin.php'" height="20px">
    </div>
    <div class="Content__center__tweets">
        <h3 class="Content__center__title">Create new retweet</h3>

        <div class="Content__tweets__retweet">
            <div>
                <img class="Content__avatar__tweets" src="img/<?=$userData['linkPhoto']?>">
            </div>
            <div>
                <div class="Content__info__tweets">
                    <div class="Content__username__tweets"><?=$userData['userName']?></div>
                    <div class="Content__nickname__tweets"><?="<a href=" . "'" . "profileuser.php" . "?user=" . $userData['userNickname'] . "'" . ">" . "@" . $userData['userNickname'] . "</a>"?></div>
                    <div class="Content__date__tweets"><?=$resultTweet['tweetDateCreate']?></div>
                </div>
                <div class="Content__text__tweets">
                    <?=$resultTweet['tweetText']?>
                </div>
                <div class="Content__hashtag">
                    <?php if (!empty($titleAll)):?>
                        <?php foreach($titleAll as $key):
                            $tagId = $key['id_tag'];
                            $selectAllTags = "SELECT title FROM Hashtags WHERE id='$tagId'";?>
                            <?php if (!empty(ConnectDB($selectAllTags))):
                                $allTags = ConnectDB($selectAllTags);?>
                                <?php foreach ($allTags as $tag): ?>
                                    <a href=<?= "'" . "hashtagpage.php" . "?title=" . $tag['title'] . "'"?>><?="#" . $tag['title'] . "&nbsp;";?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <form class="Content__form__tweets" action="retweetLogic.php" method="POST">
            <textarea class="Content__form__textaria" placeholder="Your text" name="text" type="text" size="40"></textarea><br>
            <input class="Content__form__input" placeholder="Введите тэги через пробел без #" name="hashtag" type="text" size="50"><br>
            <input type="hidden" name="idRetweet" value="<?=$idTweet?>"><br>
            <button class="Content__form__button" type="submit">SEND</button><br>
        </form>
    </div>
</body>
</html>
