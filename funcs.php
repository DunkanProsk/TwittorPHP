<?php

session_start();

function ConnectDB($query) {
    $disallow = [';'];
    $query = str_replace($disallow, '', $query);

    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'Twitter';

    $mysql = mysqli_connect($hostname, $username, $password, $database);
    return mysqli_query($mysql, $query);
}

function FetchAssoc($query) {
    $disallow = [';'];
    $query = str_replace($disallow, '', $query);
    return mysqli_fetch_assoc(ConnectDB($query));
}

function FetchAll($query) {
    $disallow = [';'];
    $query = str_replace($disallow, '', $query);
    return mysqli_fetch_all(ConnectDB($query));
}

?>
