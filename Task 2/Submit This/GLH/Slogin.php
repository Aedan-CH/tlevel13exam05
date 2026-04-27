<?php

session_start();

require_once "assets/dbconn.php";
require_once "assets/common.php";

if (isset($_SESSION["Puser"])) {
    $_SESSION["usermessage"] = "You are already logged in!"; #message for when customer is already logged in
    header("Location: index.php");
    exit; //stops anything else loading as header only works when nothing else is loading


} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usr = Slogin(dbconnect_insert(), $_POST["email"]);

    if ($usr && password_verify($_POST["password"], $usr["password"])) { //verifies the password is matched
        $_SESSION["Puser"] = true;
        $_SESSION["ProducerID"] = $usr["ProducerID"];
        $_SESSION["usermessge"] = "Successfully logged in!";
        #audtitor(dbconnect_insert(),$_SESSION["customerid"],"log", "User has successfully logged in");
        header("Location: index.php"); //redirect on success
        exit;
    } else {
        $_SESSION["usermessage"] = "Incorrect email or password!";
        if ($usr["ProducerID"]) {
            #audtitor(dbconnect_insert(), $usr["ProducerID"], "flo", "User has unsuccessfully logged in");
        }
        header("Location: Slogin.php");
        exit; //stop further execution
    }
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>";
echo "Passcheck";
echo "</title>";
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />";  # links to the external style sheet
echo "</head>";
echo "<body>";

require_once "assets/topbar.php";
require_once "assets/nav.php";

echo "<div class='container'>";
echo "<div id='content'>";
echo "<h2 id='passcheck'>";
echo "<u>";
echo "Enter Producer email and password"; # asks for username and password
echo "</u>";
echo "</h2>";
echo "<br>";

echo "<form action='Slogin.php' method='post'>"; #Forming

echo "<p id='ptext'>";
echo "Enter email here:";
echo "</p>";
echo "<input type='email' name='email' placeholder='E-mail Address' required/>";


echo "<p id='ptext'>";
echo "Enter password here:";
echo "</p>";
echo "<input type='password' name='password' placeholder='Enter password here...' required>";
echo "<br>";
echo "<input type='submit' value='Check Password'>"; #The submit button.
echo "</form>";