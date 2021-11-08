<?php

/**
 * 
 * @checkuser.php
 * 
 * Check whether the username suggestion is already taken.
 * 
 */

include 'config.php';

$username = filter_var($_POST['suggestion'], FILTER_SANITIZE_SPECIAL_CHARS);

if (isset($username)) {

    $checkuser = $conn->query("SELECT * FROM Users WHERE user='$username'");

    if ($checkuser->num_rows) {
        echo "<span class='taken'>&nbsp;&#x2718;
        The username is taken</span>";
    } else {
        echo "<span class='available'>&nbsp;&#x2714; " .
        "The username is available</span>";
    }
}