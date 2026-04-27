<?php
echo "<div class='navi'>";
echo "<nav>";  #decales

    echo "<ul>";  #declares an unordered list


        echo "<li class='linkbox'> <a href='index.php'>Index</a></li>"; #open a cell for a link to be housed

        if(!isset($_SESSION["user"])){
            echo "<li class='linkbox'> <a href='login.php'>Login</a></li>"; # link to the login page
            echo "<li class='linkbox'> <a href='register.php'>Register</a></li>"; # link to the register page
            echo "<li class='linkbox'> <a href='aboutus.php'>About us</a></li>"; # link to the register page
        } else {
            echo "<li class='linkbox'> <a href='order.php'>Order</a></li>"; # link to
            echo "<li class='linkbox'> <a href='logout.php'>Logout</a></li>"; # link to logout page
        }

    echo "</ul>";  # closes the row of the table.

echo "</nav>"; #end of nav bar

echo "</div>";
?>

