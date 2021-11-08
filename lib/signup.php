<?php

/**
 * 
 * @signup.php
 * Handle the signing up to IMARE 
 */

require_once 'config.php';

$firstName = filter_var($_POST['f_name'], FILTER_SANITIZE_SPECIAL_CHARS);
$lastName = filter_var($_POST['l_name'], FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
$username = filter_var($_POST['usname'], FILTER_SANITIZE_SPECIAL_CHARS);
$password = $_POST['pass'];
$passwordRep = $_POST['repeat_pass'];

$check_email = $conn->query("SELECT * FROM Users WHERE email = '$email");

if ($check_email->num_rows) {
    echo "email";
} else {

    //$check_email->close();

    $insert_user = $conn->prepare("INSERT INTO Users VALUES (NULL, ?, ?, ?, ?, ?)");

    if (!$insert_user) {
        echo 'error';
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $insert_user->bind_param("sssss", $firstName, $lastName, $email, $username, $password);
        $insert_user->execute();

        $_SESSION['user'] = $username;
        $_SESSION['password'] = $password;

        echo "success";
    }
}
