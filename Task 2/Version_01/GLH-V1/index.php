<?php

if (!isset($_GET["message"])) {
    session_start();
    $message = false;
} else {
    $message = htmlspecialchars(urldecode($_GET["message"]));
}

require_once "assets/dbconn.php"; # accesses the database
require_once "assets/common.php"; # access the subprograms


echo "<!DOCTYPE html>";  # essential html line to dictate the page type

echo "<html>";  # opens the html content of the page

echo "<head>";  # opens the head section

echo "<title>Greenfield Local Hub</title>";  # sets the title of the page (web browser tab)
echo "<link rel='stylesheet' type='text/css' href='css/styles.css' />";  # links to the external style sheet

echo "</head>";  # closes the head section of the page

echo "<body>";  # opens the body for the main content of the page.

echo "<div class='container'>";

require_once "assets/topbar.php";

require_once "assets/nav.php"; # acessing the navigation so that users can

echo "<div class='content'>";
echo "<br>";

echo "<h2> Welcome to Greenfield local Hub (GLH)</h2>";  # sets a h2 heading as a welcome

echo "<p class='content'>GLH is a cooperative of your local farmers and food producers</p>"; #explaining what GLH is to users

echo "<p class='content'>on this website you can order from our list of producers, or you can learn the benefits of buying from us</p>";

echo "<img src=images/vegetables.jpg>";  # image added to improve the appearance of the index page.

//try {
//    $conn = dbconnect_insert();
//    echo "success";
//} catch (PDOException $e) {
//    echo $e->getMessage();
//}
if (!$message) {
    echo user_message();
} else {
    echo $message;
}

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>