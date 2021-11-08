<?php

/**
 * 
 * @profile.php
 * 
 * Display all images that the user has uploaded to the site, uses bootstrap grid to do it dynamically.
 * 
 */

require_once 'config.php';
include_once 'header.php';

$user = $_SESSION['user'];
$sql = "SELECT * FROM Images WHERE creator = '$user'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die($conn->error);
} else {

    echo "<center><div class='profCont container'><h1>Hello " . $user . ", you have uploaded " . $result->num_rows . " images.</h1></div></center>";

    echo "<div class='my-4 container'><div class='row'>";
    while ($row_data = mysqli_fetch_array($result)) {
        if (empty($row_data)) {
            echo "<center>you haven't uploaded any images.</center>";
        } else {
            echo "<div class='col-md-4 col-lg-3 my-3'><div class='thumbnail py-3'>";
            echo "<img class='profImg img-responsive' src='";
            echo $row_data[2];
            echo "'></img>";
            echo "<div class='caption'>";
            echo $row_data[3];
            echo "</div>";
            echo "</div></div>";
        }
    }

    echo "</div></div>";
}
