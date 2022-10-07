<?php 

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: signin.php");
}

$idTweet = $_GET['id_tweet'];
$id = $_SESSION['id'];

if (!empty($_POST)) {

    $id = $_SESSION['id'];
    $text = $_POST['text'];
    $hashtags = explode(" ", $_POST['hashtag']);

    $updateTweet = "UPDATE Tweets SET tweetText=" . "\"" . $text . "\"" . " WHERE id='$idTweet'";
    ConnectDB($updateTweet);

    header("location: ./index.php");
}

header("location: ./index.php");

?>