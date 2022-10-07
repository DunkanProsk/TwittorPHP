<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: signin.php");
}

$id = $_SESSION['id'];
$tag = $_GET['title'];

$selectIdTags = "SELECT id FROM Hashtags WHERE title='$tag'";
$idTag = FetchAssoc($selectIdTags) ? FetchAssoc($selectIdTags)['id'] : null;

$selectTweetId = "SELECT id_tweet FROM TweetHashtag WHERE id_tag='$idTag'";
$idTweetAll = ConnectDB($selectTweetId);

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
    <title>Hashtag #<?=$tag?></title>
</head>
<body>
    
    <div class="Content__center">
        <h2 class="Content__title">Tweets: <?= "#" . $tag?></h2>
        <?php foreach($idTweetAll as $idTweet):
            // $str = implode(",", $idTweetAll);
            // $selectSQLTweets = "SELECT * FROM Tweets WHERE id IN ('implode()')";
        
            $selectSQLTweets = "SELECT * FROM Tweets WHERE id=" . $idTweet['id_tweet'];
            $result = ConnectDB($selectSQLTweets);?>

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
        <?php endforeach; ?>
    </div>
</body>
</html>
