<?php

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: ./signin.php");
}

if (!empty($_POST)) {

    $id = $_SESSION['id'];
    $text = $_POST['text'];
    $hashtags = explode(" ", $_POST['hashtag']);
    $idRetweet = $_POST['idRetweet'];

    $insertTweet = "INSERT INTO Tweets (idCreator, tweetText, tweetDateCreate) VALUES ('$id', '$text', CURRENT_TIMESTAMP)";
    ConnectDB($insertTweet);

    $selectLastTweet = "SELECT id FROM Tweets WHERE idCreator='$id' ORDER BY tweetDateCreate DESC LIMIT 1";
    $idTweetLast = FetchAssoc($selectLastTweet)['id'];

    $insertRetweet = "INSERT INTO Retweet (tweet_id, retweet_id) VALUES ('$idTweetLast', '$idRetweet')";

    ConnectDB($insertRetweet);

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
