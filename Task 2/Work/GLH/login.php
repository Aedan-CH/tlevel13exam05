<?php
session_start(); # start PHP session to track user login status

require_once "assets/dbconn.php"; # include database connection functions
require_once "assets/common.php"; # include common utility functions

# Redirect if user is already logged in
if (isset($_SESSION["user"])) {
    $_SESSION["usermessage"] = "You are already logged in!"; # message for already logged in user
    header("Location: index.php"); # redirect to homepage
    exit; # stop further execution
}

# Handle login form submission
elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $usr = login(dbconnect_insert(), $_POST); # attempt to find user by email

    if ($usr && password_verify($password, $usr["password"])) { # check password
        $_SESSION["user"] = $usr; # store user info in session
        $_SESSION["CustomerID"] = $usr["CustomerID"]; # store CustomerID
        $_SESSION["usermessage"] = "Successfully logged in!"; # success message
        # auditor(dbconnect_insert(), $_SESSION["CustomerID"], "log", "User has successfully logged in");
        header("Location: index.php"); # redirect on success
        exit; # stop further execution
    } else {
        $_SESSION["usermessage"] = "Incorrect email or password!"; # error message
        # if ($usr && isset($usr["CustomerID"])) {
        #     auditor(dbconnect_insert(), $usr["CustomerID"], "flo", "User has unsuccessfully logged in");
        # }
        header("Location: login.php"); # redirect back to login page
        exit; # stop further execution
    }
}

echo "<!DOCTYPE html>"; # HTML5 document type declaration
echo "<html lang='en'>"; # start of HTML
echo "<head>"; # start of head section
echo "<meta charset='UTF-8'>"; # charset for proper encoding
echo "<title>Passcheck</title>"; # page title
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />"; # stylesheet
echo "</head>";
echo "<body>"; # start of body

require_once "assets/topbar.php"; # top navigation bar
require_once "assets/nav.php";    # site navigation menu

echo "<div class='container'>"; # main container
echo "<div id='content'>"; # content section
echo "<h2 id='passcheck' align='center'>"; # heading for login
echo "<u>Enter email and password</u>";
echo "</h2>";
echo "<br>"; # spacing

echo "<form action='login.php' method='post'>"; # start login form

echo "<p id='ptext'>Enter email here:</p>";
echo "<input type='email' name='email' placeholder='E-mail Address' required/>"; # email input

echo "<p id='ptext'>Enter password here:</p>";
echo "<input type='password' name='password' placeholder='Enter password here...' required>"; # password input
echo "<br>"; # line break

echo "<input type='submit' value='Check Email and Password'>"; # submit button
echo "</form>"; # end form

echo "</div>"; # close content div
echo "</div>"; # close container div
echo "</body>";
echo "</html>";