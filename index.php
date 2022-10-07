<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: signin.php");
}

$id = $_SESSION['id'];

$selectSQLUsers = "SELECT * FROM Users WHERE id='$id'";
$userData = FetchAssoc($selectSQLUsers);

$selectSQLTweets = "SELECT * FROM Tweets ORDER BY tweetDateCreate DESC";

if (array_key_exists('all', $_POST)){
    $selectSQLTweets = "SELECT * FROM Tweets ORDER BY tweetDateCreate DESC";
}

if (array_key_exists('user', $_POST)){
    $selectSQLTweets = "SELECT * FROM Tweets WHERE idCreator='$id' ORDER BY tweetDateCreate DESC";
}

if (array_key_exists('following', $_POST)){
    $selectFollowingUser = "SELECT * FROM Follows WHERE user_id=$id";
    $selectSQLTweets = "SELECT * FROM Tweets WHERE idCreator IN (SELECT follower_id FROM Follows WHERE user_id=$id) ORDER BY tweetDateCreate DESC";
}

$result = ConnectDB($selectSQLTweets);

$selectSQLFollows = "SELECT follower_id FROM Follows WHERE user_id=$id";
$resFollows = FetchAll($selectSQLFollows);

$selectSQLFollowing = "SELECT user_id FROM Follows WHERE follower_id=$id";
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
    <script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}; </script>
    <title>Twittor</title>
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
                <button class="Content__button__tweet" onclick="document.location='newTweet.php'">TWEET</button>
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

        <div class="Content__swap__tweet" >
            <form class="Content__form__swap" enctype="multipart/form-data" action="" method="POST">
                <button class="Content__swap__button1" name="all" type="submit" <?php if (array_key_exists('user', $_POST)){echo "style='font-weight:300;'";}
                    elseif (array_key_exists('following', $_POST)) {echo "style='font-weight:300;'";}?>>ALL</button><br>
            </form>
            <form class="Content__form__swap" enctype="multipart/form-data" action="" method="POST">
                <button class="Content__swap__button3" name="following" type="submit" <?php if (array_key_exists('following', $_POST)){echo "style='font-weight:900;'";}?>>FOLLOWING</button><br> 
            </form>
            <form class="Content__form__swap" enctype="multipart/form-data" action="" method="POST">
                <button class="Content__swap__button2" name="user" type="submit" <?php if (array_key_exists('user', $_POST)){echo "style='font-weight:900;'";}?>>YOUR</button><br>
            </form>
        </div>
    
        <?php foreach($result as $key):
            $idCreator = $key['idCreator'];
            $idTweet = $key['id'];
            $selectAlltitle = "SELECT id_tag FROM TweetHashtag WHERE id_tweet='$idTweet'";
            $titleAll = ConnectDB($selectAlltitle);
            $selectUser = "SELECT * FROM Users WHERE id='$idCreator'";
            $userDataTweet = FetchAssoc($selectUser);
            $selectRetweet = "SELECT retweet_id FROM Retweet WHERE tweet_id='$idTweet'";
            $retweetid = FetchAssoc($selectRetweet) ? FetchAssoc($selectRetweet)['retweet_id'] : null;?>

            <div class="Content__tweets">
                <div>
                    <img class="Content__avatar__tweets" src="img/<?=$userDataTweet['linkPhoto']?>">
                    <?php if (!empty($retweetid)):?>
                        <img class="Content__icon__tweets" src="img/RetweetUnderPhoto.svg">
                    <?php endif; ?>
                </div>
                <div>
                    <div class="Content__info__tweets">
                        <div class="Content__username__tweets"><?=$userDataTweet['userName']?></div>
                        <div class="Content__nickname__tweets"><?="<a href=" . "'" . "profileuser.php" . "?user=" . $userDataTweet['userNickname'] . "'" . ">" . "@" . $userDataTweet['userNickname'] . "</a>"?></div>
                        <div class="Content__date__tweets"><?=$key['tweetDateCreate']?></div>
                        <?php if ($userData['id'] == $idCreator || $userData['id'] == "38"):?>
                            <div class="Content__icons">
                                <?php $str = "id_tweet=" . $idTweet;?>
                                <img class="iconEdit" src="img/RectangleIcon.png" onclick="document.location='editTweet.php?<?=$str?>'">
                                <img class="iconDelete" src="img/RectangleIcon.png" onclick="document.location='deleteTweet.php?<?=$str?>'">
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if(!empty($retweetid)):
                        $selectSQLRetweets = "SELECT * FROM Tweets WHERE id=$retweetid";
                        $resultRetweet = FetchAssoc($selectSQLRetweets);
                        
                        $idCreatorRetweet = $resultRetweet['idCreator'];

                        $selectSQLDataUserRetweet = "SELECT * FROM Users WHERE id=$idCreatorRetweet";
                        $userDataRetweet = FetchAssoc($selectSQLDataUserRetweet);
                        
                        $selectAlltitleRetweet = "SELECT id_tag FROM TweetHashtag WHERE id_tweet='$retweetid'";
                        $titleAllRetweet = ConnectDB($selectAlltitleRetweet);?>
                        <div>
                            <div class="Content__tweets__retweet__index">
                                <div>
                                    <img class="Content__avatar__tweets" src="img/<?=$userDataRetweet['linkPhoto']?>">
                                </div>
                                <div>
                                    <div class="Content__info__tweets">
                                        <div class="Content__username__tweets"><?=$userDataRetweet['userName']?></div>
                                        <div class="Content__nickname__tweets"><?="<a href=" . "'" . "profileuser.php" . "?user=" . $userDataRetweet['userNickname'] . "'" . ">" . "@" . $userDataRetweet['userNickname'] . "</a>"?></div>
                                        <div class="Content__date__tweets"><?=$resultRetweet['tweetDateCreate']?></div>
                                    </div>
                                    <div class="Content__text__tweets">
                                        <?=$resultRetweet['tweetText']?>
                                    </div>
                                    <?php if (!empty(mysqli_fetch_assoc($titleAllRetweet))):?>
                                        <div class="Content__hashtag">
                                            <?php foreach($titleAllRetweet as $keyR):
                                                $tagIdRetweet = $keyR['id_tag'];
                                                $selectAllTagsRetweet = "SELECT title FROM Hashtags WHERE id='$tagIdRetweet'";?>
                                                <?php if (!empty(ConnectDB($selectAllTagsRetweet))):
                                                    $allTagsRetweet = ConnectDB($selectAllTagsRetweet);?>
                                                    <?php foreach ($allTagsRetweet as $tag): ?>
                                                        <a href=<?= "'" . "hashtagpage.php" . "?title=" . $tag['title'] . "'"?>><?="#" . $tag['title'] . "&nbsp;";?></a>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>    
                    <?php endif; ?>

                    <div class="Content__text__tweets">
                        <?=$key['tweetText']?>
                    </div>

                    <?php if (!empty(mysqli_fetch_assoc($titleAll))):?>
                        <div class="Content__hashtag">
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
                        </div>
                    <?php endif; ?>

                    <div>
                        <form action="retweet.php" method="POST">
                            <input type="hidden" name="idTweet" value="<?=$idTweet?>">
                            <button class="iconRetweet" type="submit"></button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
