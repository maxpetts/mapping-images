<?php

/**
 * 
 * @signup.php
 * 
 * Checks whether the email is already associated with an account, if not
 * then add all the details into the Users table.
 */

require_once 'config.php';

$firstName = filter_var($_POST['f_name'], FILTER_SANITIZE_SPECIAL_CHARS);
$lastName = filter_var($_POST['l_name'], FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
$username = filter_var($_POST['usname'], FILTER_SANITIZE_SPECIAL_CHARS);
$password = $_POST['pass'];

if ($check_email = $conn->prepare("SELECT * FROM Users WHERE email = ?")) {
    $check_email->bind_param("s", $email);
    $check_email->execute();

    if ($check_email->affected_rows > 0) {
        echo "email";
    }

    console_log($email);

    $check_email->close();
    console_log('after l=close');
} else {
    $insert_user = $conn->prepare("INSERT INTO Users VALUES (NULL, ?, ?, ?, ?, ?)");

    $password = password_hash($password, PASSWORD_DEFAULT);

    $insert_user->bind_param("sssss", $firstName, $lastName, $email, $username, $password);
    $insert_user->execute();

    $_SESSION['user'] = $username;
    $_SESSION['password'] = $password;

    echo "success";
}
