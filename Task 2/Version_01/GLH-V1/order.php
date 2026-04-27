<?php
session_start();
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

echo "<h2>GLH ordering system</h2>";  # sets a h2 heading as a welcome

echo "<p class='content'>This system is still under development</p>"; #explaining what GLH is to users

echo "<img src=images/vegetables.jpg>";  # image added to improve the appearance of the index page.


echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
?>