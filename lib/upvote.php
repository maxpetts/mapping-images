<?php

/**
 * 
 * @upvote.php
 * 
 * Checks the user is logged in and then gets their ID, and compare this to the values in the Votes table,
 * if the user hasn't already voted on a image then add their ID and image ID to Votes table.
 * 
 * Doesn't allow unvoting (voting is permanent)..
 * 
 */

require_once 'config.php';

$user = $_SESSION['user'];
$imgID = $_POST['img_id'];

if (isset($user)) {
    $sql = "SELECT id FROM Users WHERE user = '$user'";
    $result = mysqli_query($conn, $sql);
    $id_res = mysqli_fetch_assoc($result);
    $id = $id_res['id'];

    $sql = "SELECT * FROM Votes WHERE userID = '$id' AND picID = '$imgID'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows >= 1) {
        echo 'dupe';
    } else {
        $sql = "INSERT INTO Votes VALUES ($id, $imgID)";
        if ($result = mysqli_query($conn, $sql)) {
            echo 'complete';
        } else {
            echo 'error ' . $conn->error;
        }
    }
}
