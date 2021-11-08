<?php

/**
 * 
 * @logout.php
 * 
 * Unset all the session variables and destroy the current session, in order to log out the user.
 * Then redirects to the home page.
 * 
 */

include_once 'config.php';

session_unset();
session_destroy();

header('Location: ../www/index.php');
