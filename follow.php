<?php 

include "./funcs.php";

if (empty($_SESSION)) {
    header("location: signin.php");
}

$idUser = $_POST['id_user'];
$nicknameUser = $_POST['nick_user'];
$id = $_SESSION['id'];

$selectUserFollow = "SELECT * FROM Follows WHERE user_id='$id' AND follower_id='$idUser'";

if(empty(FetchAssoc($selectUserFollow))) {
    $insertFollow = "INSERT INTO Follows (user_id, follower_id) VALUE ('$id', '$idUser')";
    ConnectDB($insertFollow);
}

$str = "location: profileuser.php?user=" . $nicknameUser;

header($str);

?>