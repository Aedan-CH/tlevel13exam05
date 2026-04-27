<?php

if (!isset($_GET["message"])) { #if website doesnt send a message
    session_start(); # website continues as normal
    $message = false; # Set message to false if none is provided
} else { # otherwise
    $message = htmlspecialchars(urldecode($_GET["message"])); # display message
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

echo "<div class='container'>";  # main container for the page layout

require_once "assets/topbar.php"; # access topbar that will contain navigation

require_once "assets/nav.php"; # acessing the navigation so that users can

echo "<div class='content'>";  # content section wrapper
echo "<br>";  # line break for spacing

echo "<h2> Welcome to Greenfield local Hub (GLH)</h2>";  # sets a h2 heading as a welcome

echo "<p class='content'>GLH is a cooperative of your local farmers and food producers</p>"; #explaining what GLH is to users

echo "<p class='content'>on this website you can order from our list of producers, or you can learn the benefits of buying from us</p>"; # additional info for users

echo "<img src=images/vegetables.jpg>";  # image added to improve the appearance of the index page.

if (!$message) {  # check if there is no message
    echo user_message();  # display default user message
} else {  # otherwise
    echo $message;  # display the provided message
}

echo "</div>";  # close content section

echo "</div>";  # close main container

echo "</body>";  # close body section

echo "</html>";  # close HTML document
?>