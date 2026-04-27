<?php
session_start();

#auditor(dbconnect_insert(), getnewuserid(dbconnect_insert(), $_POST['email']), "reg", "User logged out"); # logout message

session_destroy(); # ends the session

header("location:index.php?message=you have been logged out"); # logout message