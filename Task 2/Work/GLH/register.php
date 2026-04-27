<?php
session_start(); # start session to track user data

require_once "assets/common.php"; # include common utility functions
require_once "assets/dbconn.php"; # access database connection

# Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $Fname = trim($_POST["Fname"]);
    $Lname = trim($_POST["Lname"]);
    $address = trim($_POST["address"]);

    # Check if user with this email does NOT already exist
    if (!only_user(dbconnect_insert(), $email)) {

        # Attempt to register user
        if (reg_user(dbconnect_insert(), [
            "email" => $email,
            "password" => $password,
            "Fname" => $Fname,
            "Lname" => $Lname,
            "address" => $address
        ])) {
            $_SESSION["email"] = "USER WAS CREATED SUCCESSFULLY"; # confirmation message
            header("Location: login.php"); # redirect to login page
            exit;
        } else {
            $_SESSION["email"] = "ERROR: USER REGISTRATION FAILED"; # registration error
        }

    } else {
        $_SESSION["email"] = "ERROR: USER ALREADY EXISTS"; # user exists error
    }
}

echo "<!DOCTYPE html>"; # HTML5 document type
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>"; # encoding
echo "<title>Register Page</title>";
echo "<link rel='stylesheet' href='css/styles.css'>"; # stylesheet
echo "</head>";
echo "<body>";

require_once "assets/topbar.php"; # top navigation bar
require_once "assets/nav.php";    # site navigation menu

echo "<div class='container'>"; # main container
echo "<div id='content'>"; # content section
echo "<h2 id='passcheck' align='center'><u>Register Page</u></h2>";
echo "<br>";

echo "<form method='post' action=''>"; # start registration form

echo "<label for='email'>Email</label>";
echo "<input type='email' name='email' id='CustomerID' placeholder='Enter your email.' required>";
echo "<br>";

echo "<label for='password'>Password</label>";
echo "<input type='password' name='password' placeholder='Enter password here...' required>";
echo "<br>";

echo "<label for='Fname'>First name</label>";
echo "<input type='text' name='Fname' placeholder='Enter your first name.' required>";
echo "<br>";

echo "<label for='Lname'>Last name</label>";
echo "<input type='text' name='Lname' placeholder='Enter your last name' required>";
echo "<br>";

echo "<label for='Address'>Address</label>";
echo "<input type='text' name='address' placeholder='Enter your address' required>";
echo "<br>";

echo "<input type='submit' value='Submit'>"; # submit button
echo "</form>";

echo "</div>"; # close content div
echo "</div>"; # close container div
echo "</body>";
echo "</html>";