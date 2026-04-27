<?php
session_start();
require_once "assets/common.php";
require_once "assets/dbconn.php"; # access the file that connects to the database

if($_SERVER["REQUEST_METHOD"] === "POST") {
    //$_SESSION["usermessage"] = only_user(dbconnect_insert(), $_POST["username"]){
    #if(!only_Puser(dbconnect_insert(), $_POST["email"])) {
    // Add ProducerID automatically
    $_POST["ProducerID"] = (int) $_SESSION["ProducerID"];

    if (reg_product(dbconnect_insert(), $_POST )) {
        # auditor(dbconnect_insert(), getnewuserid(dbconnect_insert(), $_POST['email']), "reg", "New user registered"); # adds action of user logging in to audit log
        $_SESSION["email"] = "USER WAS CREATED SUCCESSFULLY"; # confirmation message that user was created successfully
        header("Location: Slogin.php"); # dispalys the header of login
        exit;


    } else {
        $_SESSION["email"] = "ERROR: USER REGISTRATION FAILED"; # error message incase ...
    }
} else{
    $_SESSION["email"] = "ERROR: USER REGISTRATION FAILED"; # error message incase
}
#}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";

echo "<title>Product Register Page</title>"; # title of the page
echo "<link rel='stylesheet' href='css/styles.css'>"; # accesses the stylesheet
echo "</head>";
echo "<body>";



require_once "assets/topbar.php";
require_once "assets/nav.php";

echo "<div class='container'>";
echo "<div id='content'>";
echo "<h2 id='passcheck' align='center'>";
echo "<u>";
echo "Product Register Page";
echo "</u>";
echo "</h2>";
echo "<br>";

echo "<form method='post' action=''>";

echo "<label for='name'>Product Name</label>";
echo "<input type='text' name='Pname' id='ProductID' placeholder='Enter product name' required>"; # asks for user email
echo "<br>";

echo "<label for='desc'>Product Description</label>";
echo "<input type='text' name='Pdesc' placeholder='Enter description here...' required>"; # asks for user to create password
echo "<br>";

echo "<label for='price'>Price</label>";
echo "<input type='float' name='Price' placeholder='Enter product price' required>"; # asks user to enter first name
echo "<br>";

echo "<label for='stock'>Stock</label>";
echo "<input type='int' name='Stock' placeholder='Enter amount of stock here' required>"; # asks user for last name
echo "<br>";


echo "<input type='submit' value='Submit'>"; # allowes user to submit their registration form

echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";


?>