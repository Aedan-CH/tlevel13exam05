<?php
session_start();

require_once"assets/dbconn.php";
require_once "assets/common.php";

if (isset($_SESSION["user"])){
    $_SESSION["usermessage"] = "You are already logged in!"; #message for when customer is already logged in
    header("Location: index.php");
    exit; //stops anything else loading as header only works when nothing else is loading
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usr = login(dbconnect_insert(), $_POST["email"]);

    if ($usr && password_verify($_POST["password"], $usr["password"])) { //verifies the password is matched
        $_SESSION["user"] = true;
        $_SESSION["customerid"] = $usr["customerid"];
        $_SESSION["usermessge"] = "Successfully logged in!";
        #audtitor(dbconnect_insert(),$_SESSION["customerid"],"log", "User has successfully logged in");
        header("Location: index.php"); //redirect on success
        exit;
    } else {
        $_SESSION["usermessage"] = "Incorrect email or password!";
        if($usr["CustomerID"]){
           # audtitor(dbconnect_insert(),$usr["CustomerID"],"flo", "User has unsuccessfully logged in");
        }
        header("Location: login.php");
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
echo "Enter email and password"; # asks for username and password
echo "</u>";
echo "</h2>";
echo "<br>";

echo "<form action='login.php' method='post'>"; #Forming

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

//echo "<ul id='remember'>"; #Unordered lists.
//echo "<li>The number of characters is greater than 8.</li>";
///echo "<li>At least one upper case character.</li>";
//echo "<li>At least one lower case character.</li>";
//echo "<li>At least one special character</li>";
//echo "<li>At least one number is present </li>";
//echo "<li>The first character cannot be a special character</li>";
//echo "<li>The last character cannot be the special character </li>";
//echo "<li>The word “password” cannot be part of the password</li>";
//echo "<li>The first character cannot be a number</li>";
//echo "</ul>";

echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";
?>