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

echo "<h2>About us</h2>";  # sets a h2 heading as a welcome

echo "<p class='content'>Below are the benefits from buying from your local farmers and food producers</p>"; #explaining what GLH is to users

echo "<p class='content'>
Buying local produce offers superior taste and nutrition, as food is harvested at peak ripeness and spends less time in transit. It supports the local economy by sustaining family farms and creating jobs, while significantly reducing environmental impact through fewer food miles, less packaging, and decreased carbon emissions. 
Key Benefits of Buying Local Produce:</p>";

echo "<p class='content'>Higher Nutritional Value & Taste: Produce loses nutrients quickly after harvesting; local food goes from farm to table quickly, preserving vitamins, minerals, and flavor.</p>";

echo "<p class='content'>Environmental Sustainability: Shorter transportation distances (lower food miles) reduce reliance on fossil fuels, decrease greenhouse gas emissions, and minimize the need for heavy packaging.</p>";

echo "<p class='content'>Economic Impact: Purchasing from local farmers keeps money within the community, supports local livelihoods, and preserves local farmland and green spaces.</p>";

echo "<p class='content'>Healthier and Safer Food: Local produce is often farmed with fewer chemicals, and shorter supply chains reduce the risk of contamination during transport.</p>";

echo "<p class='content'>Seasonal Diversity: Buying locally encourages eating with the seasons, which brings a wider, healthier variety of fruits and vegetables into your diet.</p>";

echo "<p class='content'>Stronger Community Connection: It fosters a direct connection with the growers, allowing consumers to know exactly how their food is produced</p>"; # end of explaining why buying local is good

if (!$message) {
    echo user_message();
} else {
    echo $message;
}

echo "</div>";

echo "</div>";

echo "</body>";

echo "</html>";
