<?php

/**
 * 
 * @login.php
 * 
 * Check the user and password hash exists on the server in the Users table and log them in.
 * 
 */


include 'config.php';

$uname = $_POST['usname'];
$pass = $_POST['pword'];

if (isset($uname) && isset($pass)) {

    $sql = "SELECT * FROM Users WHERE user='$uname'";
    $result = mysqli_query($conn, $sql);
    $hash_res = mysqli_fetch_assoc($result);
    $hash = $hash_res['passHash'];

    if ($result->num_rows && password_verify($pass, $hash)) {
        echo "success";
        $_SESSION['user'] = $uname;
        $_SESSION['pass'] = $hash;
    } else {
        echo "invalid";
    }
} else {
    header('Location: ../www/index.php');
}
