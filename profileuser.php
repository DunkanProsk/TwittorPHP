<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: signin.php");
}

$id = $_SESSION['id'];
$userNick = $_GET['user'];

$selectDataUser = "SELECT * FROM Users WHERE userNickname='$userNick'";
$userData = FetchAssoc($selectDataUser);

$selectSQLTweets = "SELECT * FROM Tweets WHERE idCreator=" . $userData['id'] . " ORDER BY tweetDateCreate DESC";
$result = ConnectDB($selectSQLTweets);

if ($id == $userData['id']) {
    header("location: index.php");
}

$idUser = $userData['id'];

$selectUserFollow = "SELECT * FROM Follows WHERE user_id='$id' AND follower_id='$idUser'";

$selectSQLFollows = "SELECT follower_id FROM Follows WHERE user_id=$idUser";
$resFollows = FetchAll($selectSQLFollows);

$selectSQLFollowing = "SELECT user_id FROM Follows WHERE follower_id=$idUser";
$resFollowing = FetchAll($selectSQLFollowing);

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
    <title><?=$userData['userName'];?></title>
</head>
<body>
    <div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='index.php'" src="img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='index.php'">TWITTOR</div>
        <img class="Content__header__profile" src="img/Profile.svg" alt="Login" onclick="document.location='signin.php'" height="20px">
    </div>
    <div class="Content__center">
        <div class="Content__info">
            <div class="Content__info__name">
                <img class="Content__avatar" src="img/<?=$userData['linkPhoto']?>">
                <h1 class="Content__username"><?=$userData['userName']?></h1>
                <?php if (empty(FetchAssoc($selectUserFollow))): ?> 
                    <form action="follow.php" method="POST">
                        <input type="hidden" name="id_user" value="<?=$userData['id']?>">
                        <input type="hidden" name="nick_user" value="<?=$userData['userNickname']?>">
                        <button type="submit" class="Content__button__tweet">FOLLOW</button>
                    </form>
                <?php else: ?>
                    <form action="unfollow.php" method="POST">
                        <input type="hidden" name="id_user" value="<?=$userData['id']?>">
                        <input type="hidden" name="nick_user" value="<?=$userData['userNickname']?>">
                        <button type="submit" class="Content__button__tweet__unfollow">UNFOLLOW</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="Content__info__bio">
                <?=$userData['userBio']?>
            </div>
            <div class="Content__follows">
                <?php
                    $followers = 0;
                    $following = 0;

                    if (!empty($resFollows)) {
                        $followers = count($resFollows);
                    }

                    if (!empty($resFollowing)) {
                        $following = count($resFollowing);
                    }
                ?>

                <div class="Content__follows__followers">Followers: <?=$following?></div>
                <div class="Content__follows__following">Following: <?=$followers?></div>
            </div>
        </div>
        
        <h2 class="Content__title">Tweets:</h2>
    
        <?php foreach($result as $key):
            $idCreator = $key['idCreator'];
            $idTweet = $key['id'];
            $selectAlltitle = "SELECT id_tag FROM TweetHashtag WHERE id_tweet='$idTweet'";
            $titleAll = ConnectDB($selectAlltitle);
            $selectUser = "SELECT * FROM Users WHERE id='$idCreator'";
            $userDataTweet = FetchAssoc($selectUser); ?>

            <div class="Content__tweets">
                <div>
                    <img class="Content__avatar__tweets" src="img/<?=$userDataTweet['linkPhoto']?>">
                </div>
                <div>
                    <div class="Content__info__tweets">
                        <div class="Content__username__tweets"><?=$userDataTweet['userName']?></div>
                        <div class="Content__nickname__tweets"><?="<a href=" . "'" . "profileuser.php" . "?user=" . $userDataTweet['userNickname'] . "'" . ">" . "@" . $userDataTweet['userNickname'] . "</a>"?></div>
                        <div class="Content__date__tweets"><?=$key['tweetDateCreate']?></div>
                    </div>
                    <div class="Content__text__tweets">
                        <?=$key['tweetText']?>
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
        <?php endforeach; ?>
    </div>
</body>
</html>
