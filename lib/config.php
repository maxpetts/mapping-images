<?php

/**
 * 
 * @config.php
 * 
 * Connect to the database and generate the tables needed for this web service.
 * Also contains a few debugging functions that enables me to output variables.
 * 
 */

 // CHANGE THIS TO SERVER SET UP
$conn = new mysqli("localhost", "root", "password", "img_db");

if ($conn->connect_error) {
    echo "Couldn't connect to database";
}

session_start();

$create_users = "CREATE TABLE IF NOT EXISTS Users (
	id SMALLINT(20) NOT NULL UNIQUE AUTO_INCREMENT,
    fname VARCHAR(20),
    lname VARCHAR(20),
    email VARCHAR(40) NOT NULL,
    user VARCHAR(20) NOT NULL UNIQUE,
    passHash VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
    )";

$create_images = "CREATE TABLE IF NOT EXISTS Images (
    id SMALLINT(20) NOT NULL UNIQUE AUTO_INCREMENT,
    absPath VARCHAR(100) NOT NULL UNIQUE,
    serverPath VARCHAR(100) NOT NULL UNIQUE,
    imgName VARCHAR(40) NOT NULL,
    creator VARCHAR(20) NOT NULL,
    longit VARCHAR(25),
    latit VARCHAR(25),
    PRIMARY KEY (id),
    FOREIGN KEY (creator) REFERENCES Users(user)
    )";

$create_votes = "CREATE TABLE IF NOT EXISTS Votes (
    userID SMALLINT(20) NOT NULL,
    picID SMALLINT(20) NOT NULL,
    PRIMARY KEY (userID, picID),
    FOREIGN KEY (userID) REFERENCES Users(id),
    FOREIGN KEY (picID) REFERENCES Images(id)
    )";

if (!$conn->query($create_users)) {
    echo "Error creating 'Users' table: " . $conn->error . "<br>";
}

if (!$conn->query($create_images)) {
    echo "ERROR creating 'Images' table: " . $conn->error . "<br>";
}

if (!$conn->query($create_votes)) {
    echo "ERROR creating 'votes' table: " . $conn->error . "<br>";
}

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }

  
